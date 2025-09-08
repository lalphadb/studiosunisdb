#!/usr/bin/env php
<?php

/**
 * Script de gestion du serveur Vite pour StudiosDB
 * Usage: php vite-manager.php [status|stop|restart|start]
 */
$action = $argv[1] ?? 'status';

function getViteProcess()
{
    $output = [];
    exec('ps aux | grep -E "npm run dev|node.*vite" | grep -v grep', $output);
    $processes = [];
    foreach ($output as $line) {
        if (preg_match('/^\w+\s+(\d+).*?(npm run dev|node.*vite)/', $line, $matches)) {
            $processes[] = [
                'pid' => $matches[1],
                'cmd' => $matches[2],
                'line' => $line,
            ];
        }
    }

    return $processes;
}

function isViteRunning()
{
    $ch = curl_init('http://127.0.0.1:5173');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode > 0;
}

function stopVite()
{
    $processes = getViteProcess();
    if (empty($processes)) {
        echo "⚠️  Aucun processus Vite trouvé\n";

        return false;
    }

    foreach ($processes as $process) {
        echo "🛑 Arrêt du processus {$process['pid']} ({$process['cmd']})...\n";
        exec("kill {$process['pid']} 2>&1", $output, $return);
        if ($return === 0) {
            echo "✅ Processus {$process['pid']} arrêté\n";
        } else {
            echo "❌ Impossible d'arrêter le processus {$process['pid']}\n";
        }
    }

    sleep(1);

    return ! isViteRunning();
}

function startVite()
{
    if (isViteRunning()) {
        echo "⚠️  Vite est déjà en cours d'exécution\n";

        return false;
    }

    echo "🚀 Démarrage de Vite...\n";
    // Démarrer en arrière-plan avec nohup
    $cmd = 'nohup npm run dev > vite.log 2>&1 &';
    exec($cmd, $output, $return);

    sleep(2); // Attendre que le serveur démarre

    if (isViteRunning()) {
        echo "✅ Vite a démarré avec succès sur http://127.0.0.1:5173\n";
        echo "📝 Logs disponibles dans: vite.log\n";

        return true;
    } else {
        echo "❌ Échec du démarrage de Vite\n";
        echo "Vérifiez vite.log pour plus de détails\n";

        return false;
    }
}

// Traitement des actions
echo "🎯 StudiosDB Vite Manager\n";
echo "========================\n\n";

switch ($action) {
    case 'status':
        echo "📊 Status du serveur Vite:\n";
        echo "-------------------------\n";

        if (isViteRunning()) {
            echo "✅ Vite est EN COURS D'EXÉCUTION sur http://127.0.0.1:5173\n\n";

            $processes = getViteProcess();
            if (! empty($processes)) {
                echo "📋 Processus actifs:\n";
                foreach ($processes as $process) {
                    echo "  PID {$process['pid']}: {$process['cmd']}\n";
                }
            }

            echo "\n🌐 URLs disponibles:\n";
            echo "  - Local: http://127.0.0.1:5173\n";
            echo "  - Laravel: http://127.0.0.1:8000\n";

            echo "\n💡 Pour arrêter: php vite-manager.php stop\n";
        } else {
            echo "❌ Vite N'EST PAS en cours d'exécution\n";
            echo "\n💡 Pour démarrer: php vite-manager.php start\n";
        }
        break;

    case 'stop':
        echo "🛑 Arrêt du serveur Vite...\n";
        if (stopVite()) {
            echo "✅ Vite arrêté avec succès\n";
        } else {
            echo "⚠️  Vite pourrait encore être actif\n";
        }
        break;

    case 'start':
        echo "🚀 Démarrage du serveur Vite...\n";
        if (startVite()) {
            echo "\n✅ Vite est maintenant actif!\n";
            echo "Rafraîchissez votre navigateur avec Ctrl+Shift+R\n";
        }
        break;

    case 'restart':
        echo "🔄 Redémarrage du serveur Vite...\n";
        echo "Étape 1: Arrêt...\n";
        stopVite();
        sleep(1);
        echo "\nÉtape 2: Démarrage...\n";
        if (startVite()) {
            echo "\n✅ Vite redémarré avec succès!\n";
        }
        break;

    default:
        echo "❌ Action inconnue: $action\n";
        echo "Usage: php vite-manager.php [status|stop|restart|start]\n";
        break;
}

echo "\n";
