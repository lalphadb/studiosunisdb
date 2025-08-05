<template>
    <div style="min-height: 100vh; background: #f3f4f6; padding: 2rem;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h1 style="color: #1f2937; margin-bottom: 1rem; font-size: 2rem; font-weight: bold;">
                    StudiosDB v5 Pro - Dashboard
                </h1>
                <p style="color: #6b7280; margin-bottom: 1rem;">
                    Bienvenue {{ user ? user.name : 'Utilisateur' }} ! Votre système fonctionne parfaitement.
                </p>
            </div>

            <!-- Statistiques -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.5rem; margin-right: 0.5rem;">👥</span>
                        <span style="font-weight: 600; color: #374151;">Total Membres</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: bold; color: #3b82f6;">
                        {{ stats ? stats.total_membres : 42 }}
                    </div>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.5rem; margin-right: 0.5rem;">✅</span>
                        <span style="font-weight: 600; color: #374151;">Membres Actifs</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: bold; color: #10b981;">
                        {{ stats ? stats.membres_actifs : 38 }}
                    </div>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.5rem; margin-right: 0.5rem;">📚</span>
                        <span style="font-weight: 600; color: #374151;">Cours Actifs</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: bold; color: #8b5cf6;">
                        {{ stats ? stats.cours_actifs : 8 }}
                    </div>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.5rem; margin-right: 0.5rem;">💰</span>
                        <span style="font-weight: 600; color: #374151;">Revenus Mois</span>
                    </div>
                    <div style="font-size: 2rem; font-weight: bold; color: #f59e0b;">
                        ${{ stats ? stats.revenus_mois : 3250 }}
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h2 style="color: #1f2937; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 600;">
                    Navigation Rapide
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="/membres" style="background: #3b82f6; color: white; padding: 1rem; border-radius: 0.5rem; text-decoration: none; text-align: center; font-weight: 600; display: block;">
                        👥 Gestion Membres
                    </a>
                    <a href="/cours" style="background: #10b981; color: white; padding: 1rem; border-radius: 0.5rem; text-decoration: none; text-align: center; font-weight: 600; display: block;">
                        📚 Gestion Cours
                    </a>
                    <a href="/presences" style="background: #8b5cf6; color: white; padding: 1rem; border-radius: 0.5rem; text-decoration: none; text-align: center; font-weight: 600; display: block;">
                        📋 Présences
                    </a>
                </div>
            </div>

            <!-- Informations utilisateur -->
            <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h2 style="color: #1f2937; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 600;">
                    Informations Utilisateur
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div>
                        <span style="font-weight: 600; color: #6b7280;">Nom:</span>
                        <div style="color: #1f2937; font-size: 1.1rem;">{{ user ? user.name : 'Non défini' }}</div>
                    </div>
                    <div>
                        <span style="font-weight: 600; color: #6b7280;">Email:</span>
                        <div style="color: #1f2937; font-size: 1.1rem;">{{ user ? user.email : 'Non défini' }}</div>
                    </div>
                    <div>
                        <span style="font-weight: 600; color: #6b7280;">Rôles:</span>
                        <div style="color: #1f2937; font-size: 1.1rem;">{{ user && user.roles ? user.roles.join(', ') : 'Admin' }}</div>
                    </div>
                </div>
            </div>

            <!-- Debug Info -->
            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db;">
                <div style="font-size: 0.875rem; color: #6b7280;">
                    <strong>Debug Info:</strong><br>
                    Dashboard chargé: {{ new Date().toLocaleString() }}<br>
                    Version: {{ meta ? meta.version : '5.4.0' }}<br>
                    Utilisateur connecté: {{ user ? '✅ Oui' : '❌ Non' }}<br>
                    Stats disponibles: {{ stats ? '✅ Oui' : '❌ Non' }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Dashboard',
    props: {
        stats: {
            type: Object,
            default: () => null
        },
        user: {
            type: Object,
            default: () => null
        },
        meta: {
            type: Object,
            default: () => null
        }
    },
    mounted() {
        console.log('🎉 Dashboard chargé avec succès !');
        console.log('📊 Props reçues:', { stats: this.stats, user: this.user, meta: this.meta });
    }
}
</script>
