<footer class="bg-slate-800 border-t border-slate-700 mt-16">
    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-3 gap-8 text-sm text-slate-400">
            <!-- Informations légales -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Conformité légale</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('privacy') }}" class="hover:text-slate-300 transition duration-200">
                            Politique de confidentialité
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="hover:text-slate-300 transition duration-200">
                            Conditions d'utilisation
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Contact</h4>
                <ul class="space-y-2">
                    <li>Studios UnisDB</li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-slate-300 transition duration-200">
                            Formulaire de contact
                        </a>
                    </li>
                    <li>
                        <a href="mailto:lalpha@4lb.ca" class="hover:text-slate-300 transition duration-200">
                            lalpha@4lb.ca
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Loi 25 -->
            <div>
                <h4 class="font-semibold text-slate-300 mb-3">Protection des données</h4>
                <div class="bg-slate-700 p-4 rounded border-l-4 border-blue-400">
                    <p class="text-xs font-medium text-blue-400 mb-2">LOI 25 - QUÉBEC</p>
                    <p class="text-xs leading-relaxed">
                        Système conforme à la Loi modernisant des dispositions législatives en matière de protection des renseignements personnels.
                    </p>
                    <div class="mt-3 space-y-1">
                        <div class="flex items-center text-xs">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Chiffrement end-to-end
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Hébergement au Québec
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-6 border-t border-slate-700 text-center text-xs text-slate-500">
            <p class="mb-2">
                &copy; 2025 StudiosUnisDB - Système développé pour Studios UnisDB
            </p>
            <p>
                Conformément à la <strong>Loi 25 du Québec</strong> sur la protection des renseignements personnels.
            </p>
        </div>
    </div>
</footer>
