<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

// Force une locale UTF-8 pour le process web : sous mod_php, Apache impose LANG=C (voir
// /etc/apache2/envvars), ce qui rendait escapeshellarg() destructif sur les caractères
// accentués (noms de fichiers d'import « …complémentaire… » tronqués → « fichier introuvable »).
setlocale(LC_CTYPE, 'C.UTF-8', 'C.utf8');

if (PHP_VERSION_ID < 70000) {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}

$kernel = new AppKernel('prod', false);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
