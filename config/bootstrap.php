<?php

use DI\ContainerBuilder;

/**
 * Defines the root directory of the application.
 */
define('APP_ROOT', dirname(__DIR__));

/**
 * Register autoloader.
 */
require APP_ROOT . '/vendor/autoload.php';

/**
 *  Loads environment variables from .env.
 */
$envs = parse_ini_file(APP_ROOT . '/.env', false, INI_SCANNER_RAW);

if ($envs) {
    foreach ($envs as $key => $value) {
        $_ENV[$key] = $value;
    }
}

/**
 *  Build PHP-DI Container instance.
 */
$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(APP_ROOT . '/config/definitions.php');

/**
 * Do not use a cache in a development environment, otherwise changes
 * made to the definition files will not be loaded.
 */
if (isset($_ENV['APP_OPTIMIZED']) && $_ENV['APP_OPTIMIZED'] === "true") {
    $containerBuilder->enableCompilation(APP_ROOT . '/storage/compiled');
} else {
    $containerCacheFile = APP_ROOT . '/storage/compiled/CompiledContainer.php';

    if (is_file($containerCacheFile)) {
        unlink($containerCacheFile);
    }
}

return $containerBuilder->build();
