<!doctype html>
<html lang="fr" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title inertia><?php echo e(config('app.name', 'Studios Unis')); ?></title>
  <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
  <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
  <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
</head>
<body class="h-full antialiased">
  <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
</body>
</html>
<?php /**PATH /home/studiosdb/studiosunisdb/studiosdb/resources/views/app.blade.php ENDPATH**/ ?>