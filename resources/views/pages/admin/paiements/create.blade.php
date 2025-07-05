@extends('layouts.admin')

@section('title', 'Créer un Paiement')

@section('content')
<div class="space-y-6">
   <!-- Header -->
   <div class="bg-gradient-to-r from-yellow-500/15 via-yellow-600/20 to-orange-500/15 text-white p-6 rounded-xl shadow-md relative overflow-hidden">
       
       <div class="relative z-10 flex justify-between items-center">
           <div class="flex items-center space-x-4">
               <div class="text-4xl drop-shadow-md">💰</div>
               <div>
                   <h1 class="text-3xl font-bold tracking-tight">Créer un Paiement</h1>
                   <p class="text-lg opacity-90 font-medium">Création d'un nouvel élément</p>
               </div>
           </div>
           <div class="flex items-center">
               <a href="{{ route('admin.paiements.index') }}" 
                  class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-3 backdrop-blur-sm border border-white/20">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                   </svg>
                   <span>Retour à la liste</span>
               </a>
           </div>
       </div>
   </div>

   <!-- Formulaire -->
   <div class="bg-slate-800/40 backdrop-blur-xl/40 backdrop-blur-xl rounded-xl border border-slate-700/30/30/20p-6">
       <form method="POST" action="{{ route('admin.paiements.store') }}" class="space-y-6">
           @csrf
           
           
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <div class="md:col-span-2">
                   <label class="block text-sm font-medium text-slate-300 mb-2">Nom *</label>
                   <input type="text" name="nom" required
                          class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500"
                          placeholder="Entrez le nom">
               </div>
               <div class="md:col-span-2">
                   <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                   <textarea name="description" rows="4"
                             class="w-full bg-slate-700 border border-slate-600 text-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500"
                             placeholder="Description optionnelle"></textarea>
               </div>
           </div>
           
           <!-- Actions -->
           <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700/30/30/20>
               <a href="{{ route('admin.paiements.index') }}" 
                  class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-xl transition-colors">
                   Annuler
               </a>
               <button type="submit" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-colors">
                   Enregistrer
               </button>
           </div>
       </form>
   </div>
</div>
@endsection
