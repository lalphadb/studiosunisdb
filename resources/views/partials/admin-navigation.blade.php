{{-- Navigation avec permissions --}}
@can('viewAny', \App\Models\User::class)
<a href="{{ route('admin.users.index') }}" class="...">
    <x-admin-icon name="users" />
    <span>Utilisateurs</span>
</a>
@endcan

@can('viewAny', \App\Models\Cours::class)
<a href="{{ route('admin.cours.index') }}" class="...">
    <x-admin-icon name="cours" />
    <span>Cours</span>
</a>
@endcan
