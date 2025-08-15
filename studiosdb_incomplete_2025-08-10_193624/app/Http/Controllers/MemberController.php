<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Belt;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        $query = Member::query()->with(['ceintureActuelle']);

        if (!$request->user()->hasRole('superadmin')) {
            $query->where('ecole_id', $request->user()->ecole_id);
        }

        if ($s = $request->input('search')) {
            $query->where(function($q) use ($s){
                $q->where('prenom','like',"%$s%")
                  ->orWhere('nom','like',"%$s%")
                  ->orWhere('numero','like',"%$s%");
            });
        }
        if ($statut = $request->input('statut')) {
            $query->where('statut',$statut);
        }
        if ($belt = $request->input('belt')) {
            $query->where('ceinture_actuelle_id',$belt);
        }

        $members = $query->orderBy('nom')->paginate(20)->withQueryString();

        return Inertia::render('Members/Index', [
            'members' => $members,
            'filters' => $request->only(['search','statut','belt']),
            'belts'   => Belt::select('id','nom')->orderBy('rang')->get(),
            'canCreate' => $request->user()->can('create', Member::class),
        ]);
    }

    public function store(StoreMemberRequest $request)
    {
        $this->authorize('create', Member::class);
        $data = $request->validated();

        $member = new Member($data);
        $member->ecole_id = $request->user()->ecole_id ?? $member->ecole_id;
        $member->numero = $member->numero ?: strtoupper(Str::random(10));
        $member->save();

        return back()->with('success','Membre créé.');
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $this->authorize('update', $member);
        $member->fill($request->validated());
        $member->save();

        return back()->with('success','Membre mis à jour.');
    }

    public function destroy(Request $request, Member $member)
    {
        $this->authorize('delete', $member);
        $member->delete();
        return back()->with('success','Membre supprimé.');
    }
}
