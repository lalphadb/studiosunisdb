<!-- Section Accès système dans la fiche Membre -->
<div class="bg-slate-800/50 rounded-xl p-6 border border-slate-700/50">
  <h3 class="text-lg font-semibold text-white mb-4">Accès système et permissions</h3>
  
  <!-- Toggle accès système -->
  <div class="mb-4">
    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
      <input 
        type="checkbox" 
        v-model="form.has_system_access" 
        @change="toggleSystemAccess"
        class="accent-indigo-600" 
      />
      Autoriser l'accès au système StudiosDB
    </label>
    <p class="text-xs text-slate-500 mt-1">
      Active un compte de connexion pour ce membre
    </p>
  </div>

  <!-- Détails du compte (si accès activé) -->
  <div v-if="form.has_system_access" class="space-y-4 border-t border-slate-700/50 pt-4">
    
    <!-- Email de connexion -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">Email de connexion</label>
        <input 
          v-model="form.user_email" 
          type="email" 
          class="w-full bg-slate-900/60 text-white border border-slate-700 rounded-xl px-4 py-2.5"
          placeholder="email@exemple.com"
        />
      </div>
      
      <!-- Mot de passe -->
      <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">
          {{ form.user_id ? 'Nouveau mot de passe' : 'Mot de passe' }}
        </label>
        <input 
          v-model="form.user_password" 
          type="password" 
          class="w-full bg-slate-900/60 text-white border border-slate-700 rounded-xl px-4 py-2.5"
          :placeholder="form.user_id ? 'Laisser vide pour conserver' : 'Minimum 8 caractères'"
        />
      </div>
    </div>

    <!-- Niveau d'accès / Rôles -->
    <div>
      <label class="block text-sm font-medium text-slate-400 mb-2">Niveau d'accès</label>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        
        <!-- Membre (consultation seulement) -->
        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
               :class="form.user_roles.includes('membre') 
                 ? 'bg-green-500/20 border-green-500/50 text-green-200'
                 : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
          <input 
            type="checkbox" 
            value="membre" 
            v-model="form.user_roles"
            class="accent-green-600" 
          />
          <div>
            <div class="font-medium">Membre</div>
            <div class="text-xs opacity-75">Consulter ses cours, paiements</div>
          </div>
        </label>

        <!-- Instructeur (enseigner) -->
        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
               :class="form.user_roles.includes('instructeur') 
                 ? 'bg-purple-500/20 border-purple-500/50 text-purple-200'
                 : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
          <input 
            type="checkbox" 
            value="instructeur" 
            v-model="form.user_roles"
            class="accent-purple-600" 
          />
          <div>
            <div class="font-medium">Instructeur</div>
            <div class="text-xs opacity-75">Enseigner + gérer présences</div>
          </div>
        </label>

        <!-- Admin école -->
        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
               :class="form.user_roles.includes('admin_ecole') 
                 ? 'bg-amber-500/20 border-amber-500/50 text-amber-200'
                 : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-700/60'">
          <input 
            type="checkbox" 
            value="admin_ecole" 
            v-model="form.user_roles"
            class="accent-amber-600" 
          />
          <div>
            <div class="font-medium">Admin école</div>
            <div class="text-xs opacity-75">Gestion complète</div>
          </div>
        </label>
      </div>
    </div>

    <!-- Statut du compte -->
    <div class="flex items-center gap-4">
      <label class="inline-flex items-center gap-2 text-sm text-slate-300">
        <input 
          type="checkbox" 
          v-model="form.user_active" 
          class="accent-blue-600" 
        />
        Compte actif
      </label>
      
      <label class="inline-flex items-center gap-2 text-sm text-slate-300">
        <input 
          type="checkbox" 
          v-model="form.user_email_verified" 
          class="accent-green-600" 
        />
        Email vérifié
      </label>
    </div>

    <!-- Informations compte existant -->
    <div v-if="form.user_id" class="bg-slate-900/50 rounded-lg p-3">
      <div class="text-xs text-slate-400">
        Compte créé le {{ formatDate(membre.user?.created_at) }}
        <span v-if="membre.user?.last_login_at">
          • Dernière connexion : {{ formatDate(membre.user.last_login_at) }}
        </span>
      </div>
    </div>
  </div>
</div>