<div class="relative" 
     x-data="{ 
         darkMode: localStorage.getItem('darkMode') === 'true' || true,
         toggle() {
             this.darkMode = !this.darkMode;
             localStorage.setItem('darkMode', this.darkMode);
             if (this.darkMode) {
                 document.documentElement.classList.add('dark');
                 document.body.classList.add('bg-slate-900');
             } else {
                 document.documentElement.classList.remove('dark');
                 document.body.classList.remove('bg-slate-900');
                 document.body.classList.add('bg-white');
             }
         }
     }" 
     x-init="if (darkMode) {
         document.documentElement.classList.add('dark');
         document.body.classList.add('bg-slate-900');
     }">
    
    <button @click="toggle()" 
            class="flex items-center space-x-2 px-3 py-2 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600/30 hover:border-slate-500/50 transition-all duration-300 group">
        
        <!-- Icône et texte adaptatifs -->
        <div class="flex items-center space-x-2">
            <!-- Mode sombre (état par défaut) -->
            <template x-if="darkMode">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-medium text-slate-300 group-hover:text-white transition-colors">Sombre</span>
                </div>
            </template>
            
            <!-- Mode clair -->
            <template x-if="!darkMode">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-yellow-500 group-hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
                    </svg>
                    <span class="text-xs font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Clair</span>
                </div>
            </template>
        </div>
    </button>
</div>
