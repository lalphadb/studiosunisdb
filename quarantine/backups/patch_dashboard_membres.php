<?php
$path = __DIR__.'/web.php';
$src  = file_get_contents($path);

/* 1) /dashboard -> Controller */
$src = preg_replace(
    "#Route::get\\('/dashboard'.*?\\)->name\\('dashboard'\\);#s",
    "Route::get('/dashboard', [\\App\\Http\\Controllers\\DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');",
    $src, 1
);

/* 2) déplacer /membres/bulk dans le groupe auth (si trouvé en dehors) */
if (preg_match("#Route::post\\('/membres/bulk'.*?\\);#", $src, $m)) {
    $src = str_replace($m[0], '', $src);
    $src = preg_replace(
        "#Route::middleware\\(\\['auth'\\]\\)->group\\(function \\(\\) \\{#",
        "Route::middleware(['auth'])->group(function () {\n    Route::post('/membres/bulk', [\\App\\Http\\Controllers\\MembreController::class, 'bulk'])\n        ->middleware('can:membres.edit')\n        ->name('membres.bulk');",
        $src, 1
    );
}

file_put_contents($path, $src);
echo \"✅ routes/web.php patché (dashboard+bulk).\\n\";
