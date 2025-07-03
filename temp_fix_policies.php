<?php
// Correction temporaire pour la méthode checkPoliciesRegistration

protected function checkPoliciesRegistration()
{
    $authServiceProviderPath = app_path('Providers/AuthServiceProvider.php');
    $expectedPolicies = [
        'App\\Models\\User' => 'App\\Policies\\UserPolicy',
        'App\\Models\\Ecole' => 'App\\Policies\\EcolePolicy', 
        'App\\Models\\Cours' => 'App\\Policies\\CoursPolicy',
        'App\\Models\\Ceinture' => 'App\\Policies\\CeinturePolicy',
        'App\\Models\\Seminaire' => 'App\\Policies\\SeminairePolicy',
        'App\\Models\\Paiement' => 'App\\Policies\\PaiementPolicy',
        'App\\Models\\Presence' => 'App\\Policies\\PresencePolicy',
        'App\\Models\\SessionCours' => 'App\\Policies\\SessionCoursPolicy',
        'App\\Models\\CoursHoraire' => 'App\\Policies\\CoursHorairePolicy',
    ];
    
    $registeredPolicies = [];
    $missingPolicies = [];
    $policyFilesMissing = [];
    
    if (File::exists($authServiceProviderPath)) {
        $content = File::get($authServiceProviderPath);
        
        foreach ($expectedPolicies as $model => $policy) {
            $modelShort = class_basename($model);
            $policyShort = class_basename($policy);
            
            // Chercher dans le contenu avec les deux formats possibles
            if (preg_match("/" . preg_quote($model) . ".*" . preg_quote($policy) . "/", $content) ||
                preg_match("/\\\\{$modelShort}::class.*\\\\{$policyShort}::class/", $content)) {
                $registeredPolicies[] = $modelShort;
                
                // Vérifier si le fichier policy existe
                $policyPath = app_path('Policies/' . $policyShort . '.php');
                if (!File::exists($policyPath)) {
                    $policyFilesMissing[] = $policyShort;
                }
            } else {
                $missingPolicies[] = $modelShort;
            }
        }
        
        // Compter les policies dans le fichier pour validation
        $policiesInFile = substr_count($content, '::class =>');
        
    } else {
        $missingPolicies = array_map('class_basename', array_keys($expectedPolicies));
    }
    
    // Test pratique des policies existantes
    $policyTests = [];
    foreach ($expectedPolicies as $model => $policy) {
        if (class_exists($model) && class_exists($policy)) {
            try {
                $testUser = User::first();
                if ($testUser && method_exists($policy, 'viewAny')) {
                    // Test avec un objet de test si possible
                    if (method_exists($model, 'first')) {
                        $testModel = $model::first() ?? new $model;
                    } else {
                        $testModel = new $model;
                    }
                    $can = $testUser->can('viewAny', $testModel);
                    $policyTests[class_basename($model)] = 'testable';
                }
            } catch (\Exception $e) {
                $policyTests[class_basename($model)] = 'error: ' . substr($e->getMessage(), 0, 50);
            }
        } else {
            $policyTests[class_basename($model)] = class_exists($model) ? 'model_ok' : 'missing_model';
        }
    }
    
    $passed = count($missingPolicies) === 0 && count($policyFilesMissing) === 0;
    
    return [
        'passed' => $passed,
        'message' => $passed ? "✅ {count($registeredPolicies)} policies enregistrées: " . implode(', ', $registeredPolicies)
                            : "❌ Problèmes détectés: " . 
                              (count($missingPolicies) > 0 ? "Non enregistrées: " . implode(', ', $missingPolicies) . " " : "") .
                              (count($policyFilesMissing) > 0 ? "Fichiers manquants: " . implode(', ', $policyFilesMissing) : ""),
        'details' => [
            'registered' => $registeredPolicies,
            'missing_registration' => $missingPolicies,
            'missing_files' => $policyFilesMissing,
            'policy_tests' => $policyTests,
            'expected_count' => count($expectedPolicies),
            'found_count' => count($registeredPolicies)
        ]
    ];
}
