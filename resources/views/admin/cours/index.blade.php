<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Cours - StudiosDB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mx-auto max-w-7xl px-4-fluid">
        <div class="flex flex-wrap -mx-2">
            <div class="flex-1 px-2 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">Gestion des Cours</h1>
                        <p class="text-muted">{{ $cours->total() }} cours au total</p>
                    </div>
                    <a href="{{ route('admin.cours.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus me-1"></i> Nouveau cours
                    </a>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden-body">
                        @forelse($cours as $cour)
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="col-md-8">
                                <h5>{{ $cour->nom }}</h5>
                                <p class="text-muted">{{ $cour->description }}</p>
                                <small>Instructeur: {{ $cour->instructeur->name ?? 'Non assigné' }}</small>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cours.show', $cour) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.cours.edit', $cour) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.cours.destroy', $cour) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Supprimer ce cours ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-4x text-muted mb-3"></i>
                            <h4>Aucun cours créé</h4>
                            <p class="text-muted">Commencez par créer vos premiers cours pour organiser votre enseignement.</p>
                            <a href="{{ route('admin.cours.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn-lg">
                                <i class="fas fa-plus me-2"></i> Créer le premier cours
                            </a>
                        </div>
                        @endforelse

                        @if($cours->count() > 0)
                        {{ $cours->links() }}
                        @endif
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left me-1"></i> Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
