<template>
    <div style="padding: 40px; background: linear-gradient(45deg, #4CAF50, #45a049); min-height: 100vh; color: white; font-family: Arial, sans-serif;">
        <div style="text-align: center; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; backdrop-filter: blur(10px);">
            <h1 style="font-size: 48px; margin-bottom: 20px;">🎉 SUCCÈS !</h1>
            <h2 style="font-size: 32px; margin-bottom: 30px;">Dashboard Simple Fonctionne</h2>
            
            <div style="background: rgba(255,255,255,0.2); padding: 20px; border-radius: 10px; margin: 20px 0;">
                <p style="font-size: 20px;"><strong>Message:</strong> {{ message }}</p>
                <p style="font-size: 20px;"><strong>Utilisateur:</strong> {{ user_name }}</p>
                <p style="font-size: 16px;"><strong>Timestamp:</strong> {{ timestamp }}</p>
            </div>
            
            <div style="background: rgba(0,0,0,0.2); padding: 20px; border-radius: 10px; margin: 30px 0;">
                <h3 style="color: #FFD700; font-size: 24px;">✅ Tests Réussis</h3>
                <ul style="text-align: left; max-width: 400px; margin: 0 auto;">
                    <li>✅ Laravel Framework</li>
                    <li>✅ Inertia.js</li>
                    <li>✅ Vue.js 3</li>
                    <li>✅ Contrôleur PHP</li>
                    <li>✅ Authentification</li>
                </ul>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="/dashboard" style="background: #f44336; color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-size: 18px; margin: 10px; display: inline-block;">Dashboard Original (Problématique)</a>
                <a href="/membres" style="background: #2196F3; color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-size: 18px; margin: 10px; display: inline-block;">Page Membres (Fonctionne)</a>
            </div>
            
            <div style="margin-top: 40px; padding: 20px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                <h4 style="color: #FFD700;">🔍 Diagnostic</h4>
                <p>Si vous voyez cette page, le problème est spécifiquement dans <strong>Dashboard/Admin.vue</strong> ou <strong>DashboardController.php</strong></p>
            </div>
        </div>
    </div>
</template>

<script setup>
// Props reçues du contrôleur
defineProps({
    message: String,
    user_name: String,
    timestamp: String,
});
</script>
