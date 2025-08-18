<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Models\Member;
use App\Models\Belt;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Member::class, 'member');
    }

    public function index(Request $request): Response
    {
        $query = Member::query()
            ->with(['currentBelt', 'family'])
            ->withCount(['courses', 'attendances'])
            ->search($request->q)
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->belt_id, fn($q, $belt) => $q->where('current_belt_id', $belt))
            ->when($request->age_group, function($q, $group) {
                return $group === 'minor' ? $q->minors() : $q->adults();
            })
            ->when($request->from_date, fn($q, $date) => $q->where('registration_date', '>=', $date))
            ->when($request->to_date, fn($q, $date) => $q->where('registration_date', '<=', $date));

        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->dir === 'asc' ? 'asc' : 'desc';
        
        $members = $query->orderBy($sortField, $sortDirection)
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        $stats = [
            'total' => Member::count(),
            'active' => Member::active()->count(),
            'new_this_month' => Member::whereMonth('registration_date', now()->month)->count(),
            'minors' => Member::minors()->count(),
        ];

        return Inertia::render('Members/Index', [
            'members' => $members,
            'filters' => (object) [
                'q' => $request->q,
                'status' => $request->status,
                'belt_id' => $request->belt_id,
                'age_group' => $request->age_group,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'sort' => $sortField,
                'dir' => $sortDirection,
                'per_page' => $request->per_page ?? 15,
            ],
            'belts' => Belt::orderBy('order')->get(['id', 'name', 'color_hex']),
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Members/Create', [
            'belts' => Belt::orderBy('order')->get(['id', 'name', 'color_hex']),
            'families' => Family::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(MemberStoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            $member = Member::create($request->validated());
            
            // Log activity
            activity()
                ->performedOn($member)
                ->causedBy(auth()->user())
                ->withProperties(['action' => 'created'])
                ->log('Membre créé');
            
            DB::commit();
            
            return redirect()
                ->route('members.show', $member)
                ->with('success', 'Membre créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du membre.');
        }
    }

    public function show(Member $member): Response
    {
        $member->load([
            'currentBelt',
            'family.members',
            'courses' => fn($q) => $q->withPivot(['registration_date', 'status']),
            'attendances' => fn($q) => $q->latest()->limit(10),
            'payments' => fn($q) => $q->latest()->limit(10),
            'beltProgressions.fromBelt',
            'beltProgressions.toBelt',
        ]);

        $stats = [
            'attendance_rate' => $member->getAttendanceRate(),
            'total_attendances' => $member->attendances()->count(),
            'active_courses' => $member->courses()->wherePivot('status', 'active')->count(),
            'pending_payments' => $member->payments()->where('status', 'pending')->sum('amount'),
        ];

        return Inertia::render('Members/Show', [
            'member' => $member,
            'stats' => $stats,
            'canEdit' => auth()->user()->can('update', $member),
            'canDelete' => auth()->user()->can('delete', $member),
            'canChangeBelt' => auth()->user()->can('changeBelt', $member),
        ]);
    }

    public function edit(Member $member): Response
    {
        return Inertia::render('Members/Edit', [
            'member' => $member->load(['currentBelt', 'family']),
            'belts' => Belt::orderBy('order')->get(['id', 'name', 'color_hex']),
            'families' => Family::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(MemberUpdateRequest $request, Member $member): RedirectResponse
    {
        DB::beginTransaction();
        
        try {
            $member->update($request->validated());
            
            activity()
                ->performedOn($member)
                ->causedBy(auth()->user())
                ->withProperties(['action' => 'updated', 'changes' => $member->getChanges()])
                ->log('Membre modifié');
            
            DB::commit();
            
            return redirect()
                ->route('members.show', $member)
                ->with('success', 'Membre modifié avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification du membre.');
        }
    }

    public function destroy(Member $member): RedirectResponse
    {
        try {
            $member->delete();
            
            activity()
                ->performedOn($member)
                ->causedBy(auth()->user())
                ->withProperties(['action' => 'deleted'])
                ->log('Membre supprimé');
            
            return redirect()
                ->route('members.index')
                ->with('success', 'Membre supprimé avec succès.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du membre.');
        }
    }

    public function changeBelt(Request $request, Member $member): RedirectResponse
    {
        $this->authorize('changeBelt', $member);
        
        $request->validate([
            'belt_id' => 'required|exists:belts,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            $oldBelt = $member->currentBelt;
            $newBelt = Belt::find($request->belt_id);
            
            $member->changeBelt($newBelt);
            
            activity()
                ->performedOn($member)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'belt_changed',
                    'from' => $oldBelt?->name,
                    'to' => $newBelt->name,
                    'notes' => $request->notes,
                ])
                ->log('Changement de ceinture');
            
            DB::commit();
            
            return back()->with('success', "Ceinture changée avec succès vers {$newBelt->name}.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Erreur lors du changement de ceinture.');
        }
    }

    public function export(Request $request)
    {
        $this->authorize('export', Member::class);
        
        $filename = 'membres_' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new MembersExport($request->all()), $filename);
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $this->authorize('create', Member::class);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:members,id',
            'action' => 'required|in:activate,deactivate,suspend,delete',
        ]);
        
        DB::beginTransaction();
        
        try {
            $members = Member::whereIn('id', $request->ids)->get();
            
            foreach ($members as $member) {
                switch ($request->action) {
                    case 'activate':
                        $member->activate();
                        break;
                    case 'deactivate':
                        $member->deactivate();
                        break;
                    case 'suspend':
                        $member->suspend();
                        break;
                    case 'delete':
                        $this->authorize('delete', $member);
                        $member->delete();
                        break;
                }
            }
            
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'bulk_' . $request->action,
                    'count' => count($request->ids),
                ])
                ->log('Action en masse sur les membres');
            
            DB::commit();
            
            return back()->with('success', count($request->ids) . ' membres traités avec succès.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Erreur lors du traitement en masse.');
        }
    }
}
