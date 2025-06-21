@extends('layouts.admin')

@section('title', $ecole->nom)

@section('content')
<div class="admin-content">
   <!-- Header École -->
   <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-6 text-white shadow-xl mb-6">
       <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
           <div class="flex items-center mb-4 lg:mb-0">
               <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                   <span class="text-2xl font-bold">{{ $ecole->code }}</span>
               </div>
               <div>
                   <h1 class="text-3xl font-bold">{{ $ecole->nom }}</h1>
                   <p class="text-green-100">{{ $ecole->ville }}, {{ $ecole->province }}</p>
                   <div class="flex items-center mt-2">
                       @if($ecole->active)
                           <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">École Active</span>
                       @else
                           <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">École Inactive</span>
                       @endif
                   </div>
               </div>
           </div>
           
           <div class="flex space-x-3">
               @can('edit-ecole')
               <a href="{{ route('admin.ecoles.edit', $ecole) }}" class="btn-secondary">
                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                   </svg>
                   Modifier École
               </a>
               @endcan
               
               <a href="{{ route('admin.ecoles.index') }}" class="btn-secondary">
                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                   </svg>
                   Retour à la liste
               </a>
           </div>
       </div>
   </div>

   <!-- Statistiques de l'école -->
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
       <div class="admin-card p-6">
           <div class="flex items-center">
               <div class="p-3 bg-blue-500 bg-opacity-20 rounded-full">
                   <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                   </svg>
               </div>
               <div class="ml-4">
                   <h3 class="text-sm font-medium text-gray-400">Total Utilisateurs</h3>
                   <p class="text-2xl font-bold text-white">{{ $stats['total_users'] }}</p>
               </div>
           </div>
       </div>

       <div class="admin-card p-6">
           <div class="flex items-center">
               <div class="p-3 bg-purple-500 bg-opacity-20 rounded-full">
                   <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                   </svg>
               </div>
               <div class="ml-4">
                   <h3 class="text-sm font-medium text-gray-400">Instructeurs</h3>
                   <p class="text-2xl font-bold text-white">{{ $stats['instructeurs'] }}</p>
               </div>
           </div>
       </div>

       <div class="admin-card p-6">
           <div class="flex items-center">
               <div class="p-3 bg-green-500 bg-opacity-20 rounded-full">
                   <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                   </svg>
               </div>
               <div class="ml-4">
                   <h3 class="text-sm font-medium text-gray-400">Cours Actifs</h3>
                   <p class="text-2xl font-bold text-white">{{ $stats['cours_actifs'] }}</p>
               </div>
           </div>
       </div>

       <div class="admin-card p-6">
           <div class="flex items-center">
               <div class="p-3 bg-yellow-500 bg-opacity-20 rounded-full">
                   <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                   </svg>
               </div>
               <div class="ml-4">
                   <h3 class="text-sm font-medium text-gray-400">Revenus Mois</h3>
                   <p class="text-2xl font-bold text-white">${{ number_format($stats['paiements_mois'], 0) }}</p>
               </div>
           </div>
       </div>
   </div>

   <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
       <!-- Informations École -->
       <div class="admin-card">
           <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4">
               <h3 class="text-lg font-bold text-white flex items-center">
                   <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                       <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                   </svg>
                   Informations de l'École
               </h3>
           </div>
           <div class="p-6 space-y-4">
               <div>
                   <label class="block text-sm font-medium text-gray-400 mb-1">Adresse Complète</label>
                   <p class="text-white">{{ $ecole->adresse }}</p>
                   <p class="text-gray-400">{{ $ecole->ville }}, {{ $ecole->province }} {{ $ecole->code_postal }}</p>
               </div>
               
               @if($ecole->telephone)
               <div>
                   <label class="block text-sm font-medium text-gray-400 mb-1">Téléphone</label>
                   <p class="text-white">{{ $ecole->telephone }}</p>
               </div>
               @endif
               
               @if($ecole->email)
               <div>
                   <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                   <p class="text-white">{{ $ecole->email }}</p>
               </div>
               @endif
               
               @if($ecole->site_web)
               <div>
                   <label class="block text-sm font-medium text-gray-400 mb-1">Site Web</label>
                   <a href="{{ $ecole->site_web }}" target="_blank" class="text-blue-400 hover:text-blue-300">{{ $ecole->site_web }}</a>
               </div>
               @endif
               
               @if($ecole->description)
               <div>
                   <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                   <p class="text-gray-300">{{ $ecole->description }}</p>
               </div>
               @endif
           </div>
       </div>

       <!-- Équipe Administrative -->
       <div class="admin-card">
           <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
               <h3 class="text-lg font-bold text-white flex items-center">
                   <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                       <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                   </svg>
                   Équipe Administrative ({{ $stats['admins'] + $stats['instructeurs'] }})
               </h3>
           </div>
           <div class="p-6">
               @if($ecole->users->where('roles.0.name', 'admin')->count() > 0)
               <div class="mb-4">
                   <h4 class="text-sm font-medium text-gray-400 mb-2">Administrateurs ({{ $stats['admins'] }})</h4>
                   <div class="space-y-2">
                       @foreach($ecole->users->filter(fn($u) => $u->roles->contains('name', 'admin')) as $admin)
                       <div class="flex items-center p-2 bg-gray-700 rounded-lg">
                           <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                               <span class="text-white text-xs font-bold">{{ substr($admin->name, 0, 2) }}</span>
                           </div>
                           <div>
                               <p class="text-white text-sm font-medium">{{ $admin->name }}</p>
                               <p class="text-gray-400 text-xs">{{ $admin->email }}</p>
                           </div>
                       </div>
                       @endforeach
                   </div>
               </div>
               @endif
               
               @if($ecole->users->filter(fn($u) => $u->roles->contains('name', 'instructeur'))->count() > 0)
               <div>
                   <h4 class="text-sm font-medium text-gray-400 mb-2">Instructeurs ({{ $stats['instructeurs'] }})</h4>
                   <div class="space-y-2">
                       @foreach($ecole->users->filter(fn($u) => $u->roles->contains('name', 'instructeur'))->take(5) as $instructeur)
                       <div class="flex items-center p-2 bg-gray-700 rounded-lg">
                           <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                               <span class="text-white text-xs font-bold">{{ substr($instructeur->name, 0, 2) }}</span>
                           </div>
                           <div>
                               <p class="text-white text-sm font-medium">{{ $instructeur->name }}</p>
                               <p class="text-gray-400 text-xs">{{ $instructeur->email }}</p>
                           </div>
                       </div>
                       @endforeach
                       @if($stats['instructeurs'] > 5)
                       <p class="text-gray-400 text-sm text-center">Et {{ $stats['instructeurs'] - 5 }} autres...</p>
                       @endif
                   </div>
               </div>
               @endif
               
               @if($stats['admins'] + $stats['instructeurs'] == 0)
               <div class="text-center py-4">
                   <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                   </svg>
                   <p class="text-gray-400">Aucun administrateur ou instructeur assigné</p>
               </div>
               @endif
           </div>
       </div>
   </div>

   <!-- Cours Actifs -->
   @if($ecole->cours->count() > 0)
   <div class="admin-card mt-6">
       <div class="bg-gradient-to-r from-orange-600 to-red-600 p-4">
           <h3 class="text-lg font-bold text-white flex items-center">
               <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
               </svg>
               Cours Actifs ({{ $ecole->cours->count() }})
           </h3>
       </div>
       <div class="p-6">
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
               @foreach($ecole->cours->take(6) as $cours)
               <div class="bg-gray-700 rounded-lg p-4">
                   <h4 class="text-white font-medium mb-2">{{ $cours->nom }}</h4>
                   <div class="text-sm text-gray-400 space-y-1">
                       <p>Niveau: <span class="text-white">{{ ucfirst($cours->niveau) }}</span></p>
                       <p>Inscriptions: <span class="text-white">{{ $cours->inscriptions_count ?? 0 }}/{{ $cours->capacite_max }}</span></p>
                       @if($cours->prix)
                       <p>Prix: <span class="text-white">${{ $cours->prix }}</span></p>
                       @endif
                   </div>
               </div>
               @endforeach
           </div>
           @if($ecole->cours->count() > 6)
           <div class="text-center mt-4">
               <a href="{{ route('admin.cours.index', ['ecole' => $ecole->id]) }}" class="text-blue-400 hover:text-blue-300">
                   Voir tous les cours ({{ $ecole->cours->count() }})
               </a>
           </div>
           @endif
       </div>
   </div>
   @endif

   <!-- Séminaires à venir -->
   @if($ecole->seminaires->count() > 0)
   <div class="admin-card mt-6">
       <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
           <h3 class="text-lg font-bold text-white flex items-center">
               <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
               </svg>
               Séminaires à Venir ({{ $ecole->seminaires->count() }})
           </h3>
       </div>
       <div class="p-6">
           <div class="space-y-4">
               @foreach($ecole->seminaires->take(3) as $seminaire)
               <div class="bg-gray-700 rounded-lg p-4">
                   <div class="flex justify-between items-start">
                       <div>
                           <h4 class="text-white font-medium mb-1">{{ $seminaire->titre }}</h4>
                           <p class="text-gray-400 text-sm mb-2">{{ $seminaire->description }}</p>
                           <div class="text-sm text-gray-400">
                               <p>Date: <span class="text-white">{{ \Carbon\Carbon::parse($seminaire->date_debut)->format('d/m/Y') }}</span></p>
                               <p>Inscriptions: <span class="text-white">{{ $seminaire->inscriptions_count ?? 0 }}/{{ $seminaire->capacite_max }}</span></p>
                           </div>
                       </div>
                       <span class="bg-{{ $seminaire->type === 'technique' ? 'blue' : ($seminaire->type === 'kata' ? 'green' : 'purple') }}-500 text-white px-2 py-1 rounded text-xs">
                           {{ ucfirst($seminaire->type) }}
                       </span>
                   </div>
               </div>
               @endforeach
           </div>
           @if($ecole->seminaires->count() > 3)
           <div class="text-center mt-4">
               <a href="{{ route('admin.seminaires.index', ['ecole' => $ecole->id]) }}" class="text-blue-400 hover:text-blue-300">
                   Voir tous les séminaires ({{ $ecole->seminaires->count() }})
               </a>
           </div>
           @endif
       </div>
   </div>
   @endif
</div>
@endsection
