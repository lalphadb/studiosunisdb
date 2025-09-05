<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
use App\Models\Cours;
use App\Models\User;
use App\Services\CourseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CoursController extends Controller
{
    private function joursDisponibles(): array
    { return ['lundi'=>'Lundi','mardi'=>'Mardi','mercredi'=>'Mercredi','jeudi'=>'Jeudi','vendredi'=>'Vendredi','samedi'=>'Samedi','dimanche'=>'Dimanche']; }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Cours::class);
        $withArchives = $request->boolean('archives');
        $query = Cours::with(['instructeur','ecole'])
            ->withCount('membresActifs as membres_actifs_count')
            ->orderBy('jour_semaine')->orderBy('heure_debut');
        if ($withArchives) $query->onlyTrashed();
        $paginated = $query->paginate(15)->withQueryString();
        $paginated->getCollection()->transform(function($c){
            $c->jour_semaine_display = ucfirst($c->jour_semaine);
            $c->heure_debut_format = Carbon::parse($c->heure_debut)->format('H:i');
            $c->heure_fin_format = Carbon::parse($c->heure_fin)->format('H:i');
            $c->inscrits_count = $c->membres_actifs_count; $c->is_archived = $c->deleted_at!==null; return $c; });
        $instructeurs = User::role('instructeur')->select('id','name','email')->orderBy('name')->get();
        $stats = [
            'totalCours' => Cours::count(),
            'coursActifs' => Cours::whereNull('deleted_at')->count(),
            'totalInstructeurs' => $instructeurs->count(),
            'seancesParSemaine' => Cours::whereNull('deleted_at')->count(),
        ];
        return Inertia::render('Cours/Index',[ 'cours'=>$paginated,'instructeurs'=>$instructeurs,'stats'=>$stats,
            'canCreate'=>Auth::user()?->can('create',Cours::class)??false,
            'canEdit'=>Auth::user()?->hasAnyRole(['superadmin','admin_ecole'])??false,
            'canDelete'=>Auth::user()?->hasAnyRole(['superadmin','admin_ecole'])??false,
            'canExport'=>Auth::user()?->can('export',Cours::class)??false,
            'showingArchives'=>$withArchives,'newCoursId'=>session('new_cours_id')]);
    }

    public function create(): Response
    {
        $this->authorize('create', Cours::class);
        $instructeurs = User::role('instructeur')->where('ecole_id',Auth::user()->ecole_id)
            ->orderBy('name')->get(['id','name','email']);
        return Inertia::render('Cours/Create',[ 'instructeurs'=>$instructeurs,'niveaux'=>array_keys(Cours::NIVEAUX),'joursDisponibles'=>$this->joursDisponibles() ]);
    }

    public function store(StoreCoursRequest $request, CourseService $service)
    {
        $v = $request->validated();
        if ($v['instructeur_id']) {
            $inst = User::find($v['instructeur_id']);
            if (!$inst || $inst->ecole_id !== Auth::user()->ecole_id)
                return back()->withErrors(['instructeur_id'=>'Instructeur invalide'])->withInput();
        }
        $cours = $service->create($v);
        return redirect()->route('cours.show',$cours)->with('success','Cours créé avec succès.');
    }

    public function show(Cours $cours): Response
    { $this->authorize('view',$cours); return Inertia::render('Cours/Show',[ 'cours'=>$cours ]); }

    public function edit(Cours $cours): Response
    { $this->authorize('update',$cours); $instructeurs = User::role('instructeur')->where('ecole_id',Auth::user()->ecole_id)->orderBy('name')->get(['id','name','email']);
      return Inertia::render('Cours/Edit',[ 'cours'=>$cours,'instructeurs'=>$instructeurs,'niveaux'=>array_keys(Cours::NIVEAUX),'joursDisponibles'=>$this->joursDisponibles() ]); }

    public function update(UpdateCoursRequest $request, Cours $cours, CourseService $service)
    { $this->authorize('update',$cours); $v=$request->validated(); if($v['instructeur_id']){ $inst=User::find($v['instructeur_id']); if(!$inst||$inst->ecole_id!==Auth::user()->ecole_id)return back()->withErrors(['instructeur_id'=>'Instructeur invalide'])->withInput(); }
      $service->update($cours,$v); return redirect()->route('cours.show',$cours)->with('success','Cours mis à jour avec succès.'); }

    public function destroy(Cours $cours, CourseService $service)
    { $this->authorize('delete',$cours); if(!$cours->id){ Log::warning('Destroy sans id',['route_param'=>request()->route('cours')]); return back()->withErrors(['delete'=>'Cours introuvable.']); }
      $force = request()->boolean('force'); if($force && $cours->membresActifs()->count()>0) return back()->withErrors(['delete'=>'Inscriptions actives: suppression définitive impossible.']);
      $service->delete($cours,$force); $params=[]; if($force||request()->boolean('archives')) $params['archives']=1; return redirect()->route('cours.index',$params)->with('success',$force?'Cours supprimé définitivement.':'Cours archivé avec succès.'); }

    public function duplicateJour(Request $request, Cours $cours)
    { $this->authorize('view',$cours); $this->authorize('create',Cours::class); $d=$request->validate(['nouveau_jour'=>'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche']); $n=$cours->duppliquerPourJour($d['nouveau_jour']); return redirect()->route('cours.index')->with(['success'=>'Cours dupliqué pour '.ucfirst($d['nouveau_jour']).' avec succès.','new_cours_id'=>$n->id]); }

    public function duplicateSession(Request $request, Cours $cours)
    { $this->authorize('view',$cours); $this->authorize('create',Cours::class); $d=$request->validate(['nouvelle_session'=>'required|in:automne,hiver,printemps,ete']); $n=$cours->duppliquerPourSession($d['nouvelle_session']); return redirect()->route('cours.index')->with('success','Cours dupliqué pour session '.Cours::SESSIONS[$d['nouvelle_session']].' avec succès.'); }

    public function sessionsForm(Cours $cours): Response
    { $this->authorize('update',$cours); return Inertia::render('Cours/SessionsCreate',[ 'cours'=>$cours->only(['id','nom','jour_semaine','heure_debut','heure_fin']),'joursDisponibles'=>$this->joursDisponibles() ]); }

    public function createSessions(Request $request, Cours $cours)
    { $this->authorize('update',$cours); if(!Auth::user()->hasRole('superadmin') && $cours->ecole_id!==Auth::user()->ecole_id) abort(403); $d=$request->validate([
        'jours_semaine'=>'required|array|min:1', 'jours_semaine.*'=>'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche', 'heure_debut'=>'required|date_format:H:i', 'heure_fin'=>'required|date_format:H:i|after:heure_debut', 'date_debut'=>'required|date', 'date_fin'=>'nullable|date|after:date_debut', 'frequence'=>'required|in:hebdomadaire,bihebdomadaire', 'dupliquer_inscriptions'=>'boolean'
      ]); $count=0; foreach($d['jours_semaine'] as $jour){ if($jour===$cours->jour_semaine) continue; $n=$cours->replicate(); $n->nom=$cours->nom.' ('.ucfirst($jour).')'; $n->jour_semaine=$jour; $n->heure_debut=$d['heure_debut']; $n->heure_fin=$d['heure_fin']; $n->date_debut=$d['date_debut']; $n->date_fin=$d['date_fin']; $n->created_at=now(); $n->updated_at=now(); $n->save(); $count++; } return redirect()->route('cours.index')->with('success',$count.' session(s) créée(s).'); }

    /* ===================== STUBS POUR ROUTES EXISTANTES (À RÉIMPLÉMENTER) ===================== */
    private function notImplemented(string $feature)
    { return back()->withErrors(['feature'=>"Fonctionnalité '$feature' non encore réimplémentée après refactor."]); }

    public function restore(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('restore'); }
    public function duplicate(Cours $cours){ $this->authorize('view',$cours); return $this->notImplemented('duplicate'); }
    public function annulerSession(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('annulerSession'); }
    public function reporterSession(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('reporterSession'); }
    public function inscrireMembre(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('inscrireMembre'); }
    public function desinscrireMembre(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('desinscrireMembre'); }
    public function listeMembres(Cours $cours){ $this->authorize('view',$cours); return $this->notImplemented('listeMembres'); }
    public function choisirHoraire(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('choisirHoraire'); }
    public function validerInscription(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('validerInscription'); }
    public function refuserInscription(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('refuserInscription'); }
    public function proposerAlternative(Cours $cours){ $this->authorize('update',$cours); return $this->notImplemented('proposerAlternative'); }
    public function planning(){ $this->authorize('viewAny', Cours::class); return $this->notImplemented('planning'); }
    public function export(){ $this->authorize('viewAny', Cours::class); return $this->notImplemented('export'); }
    public function statistiques(Cours $cours){ $this->authorize('view',$cours); return $this->notImplemented('statistiques'); }
    public function presences(Cours $cours){ $this->authorize('view',$cours); return $this->notImplemented('presences'); }
    // API endpoints
    public function checkDisponibilites(Request $r){ $this->authorize('viewAny', Cours::class); return response()->json(['status'=>'todo','feature'=>'checkDisponibilites']); }
    public function checkConflits(Request $r){ $this->authorize('viewAny', Cours::class); return response()->json(['status'=>'todo','feature'=>'checkConflits']); }
    public function search(Request $r){ $this->authorize('viewAny', Cours::class); return response()->json(['status'=>'todo','feature'=>'search']); }
    public function calendrier(Request $r){ $this->authorize('viewAny', Cours::class); return response()->json(['status'=>'todo','feature'=>'calendrier']); }
}
