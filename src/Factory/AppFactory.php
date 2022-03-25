<?php

declare(strict_types=1);

namespace App\Factory;

use App\App;
use Psr\Log\LoggerInterface;
use App\Handler\HttpErrorHandler;
use App\Middleware\ErrorMiddleware;
use Psr\Container\ContainerInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseFactoryInterface;

class AppFactory
{
    /**
     * Factory
     *
     * @param ContainerInterface $container
     *
     * @return App
     */
    public static function create(ContainerInterface $container): App
    {
        $app = new App(
            $container,
            ServerRequestFactory::fromGlobals(),
        );

        /**
         * Error Handler
         */
        $errorHandler = new HttpErrorHandler(
            $container->get(ResponseFactoryInterface::class),
            $container->get(LoggerInterface::class),
            $container->get('app.debug') == 'true'
        );

        $app->add(new ErrorMiddleware($errorHandler));

        /**
         * Caches the generated routing data and build the dispatcher from the cached information.
         */
        $routeCacheFile = APP_ROOT . '/storage/compiled/routes.php';
        $routeDefinitionCallback = require APP_ROOT . '/config/routes.php';

        if ($container->get('app.optimized') == 'true') {
            $app->addRoutingMiddleware($routeDefinitionCallback, $routeCacheFile);
        } else {
            $app->addRoutingMiddleware($routeDefinitionCallback);

            if (is_file($routeCacheFile)) {
                unlink($routeCacheFile);
            }
        }

        return $app;
    }
}
