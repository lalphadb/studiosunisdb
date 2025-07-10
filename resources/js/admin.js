/**
 * StudiosDB Admin - Point d'entrée principal
 * Architecture Enterprise avec lazy loading
 */

// Configuration globale StudiosDB
window.StudiosDB = window.StudiosDB || {
    version: '4.1.10.2',
    environment: 'production',
    modules: {},
    services: {},
    config: {
        debug: false,
        apiTimeout: 10000,
        retryAttempts: 3
    }
};

/**
 * Gestionnaire principal de l'administration
 */
class StudiosDBAdmin {
    constructor() {
        this.modules = new Map();
        this.isReady = false;
    }

    /**
     * Initialiser l'administration
     */
    async init() {
        try {
            console.log('🚀 StudiosDB Admin Enterprise v4.1.10.2');
            
            // Détecter la page actuelle
            const currentPage = this.detectCurrentPage();
            
            // Charger les modules nécessaires
            await this.loadPageModules(currentPage);
            
            // Initialiser les services globaux
            this.initGlobalServices();
            
            this.isReady = true;
            console.log(`✅ Admin initialisé pour: ${currentPage}`);
            
        } catch (error) {
            console.error('❌ Erreur initialisation admin:', error);
        }
    }

    /**
     * Détecter la page actuelle
     */
    detectCurrentPage() {
        const path = window.location.pathname;
        
        if (path.includes('/admin/dashboard')) return 'dashboard';
        if (path.includes('/admin/users')) return 'users';
        if (path.includes('/admin/cours')) return 'cours';
        if (path.includes('/admin/presences')) return 'presences';
        if (path.includes('/admin/paiements')) return 'paiements';
        if (path.includes('/admin/seminaires')) return 'seminaires';
        if (path.includes('/admin/ceintures')) return 'ceintures';
        if (path.includes('/admin/ecoles')) return 'ecoles';
        
        return 'default';
    }

    /**
     * Charger les modules selon la page
     */
    async loadPageModules(page) {
        // Module de base commun
        this.initCommonModules();
    }

    /**
     * Initialiser les modules communs
     */
    initCommonModules() {
        // Modules communs à toutes les pages admin
        this.initNotifications();
        this.initShortcuts();
    }

    /**
     * Initialiser les services globaux
     */
    initGlobalServices() {
        // Service de notifications
        this.initNotificationService();
        
        // Service de raccourcis clavier
        this.initKeyboardShortcuts();
        
        // Service de thème
        this.initThemeService();
    }

    /**
     * Service de notifications
     */
    initNotificationService() {
        window.StudiosDB.notify = (message, type = 'info', duration = 5000) => {
            console.log(`[${type.toUpperCase()}] ${message}`);
            
            // Ici vous pouvez ajouter votre système de notifications UI
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
            }
        };
    }

    /**
     * Raccourcis clavier
     */
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K : Recherche globale
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                console.log('🔍 Recherche globale activée');
            }
            
            // Ctrl/Cmd + D : Dashboard
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                window.location.href = '/admin/dashboard';
            }
        });
    }

    /**
     * Service de thème
     */
    initThemeService() {
        const themeToggle = document.querySelector('[data-theme-toggle]');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    }

    /**
     * Notifications système
     */
    initNotifications() {
        // Observer les messages Flash
        const flashMessages = document.querySelectorAll('[data-flash-message]');
        flashMessages.forEach(message => {
            const type = message.dataset.flashType || 'info';
            const text = message.textContent.trim();
            
            if (text && window.StudiosDB.notify) {
                window.StudiosDB.notify(text, type);
                message.style.display = 'none';
            }
        });
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', async () => {
    const admin = new StudiosDBAdmin();
    await admin.init();
    
    // Exposer globalement pour le debugging
    window.StudiosDB.admin = admin;
});

console.log('📦 StudiosDB Admin JS chargé');
