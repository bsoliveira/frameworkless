<?php

return [
    /**
     * Returns a detailed error details.
     */
    'app.debug' => DI\env('APP_DEBUG', 'false'),

    /**
     * Optimize Container and Routes settings.
     */
    'app.optimized' => DI\env('APP_OPTIMIZED', 'false'),

    /**
     * Timezone
     */
    'app.timezone' => DI\env('APP_TIMEZONE', 'UTC'),

    /**
     * Monolog
     */
    'logger.name' => 'general',
    'logger.maxfiles' => 15,
    'logger.path' => APP_ROOT . '/storage/logs/app.log',
    'logger.level' => \Monolog\Logger::DEBUG,

    /**
     * The PHP Data Objects (PDO)
     */
    'pdo.host' => DI\env('DB_HOST', 'localhost'),
    'pdo.port' => DI\env('DB_PORT', '3306'),
    'pdo.user' => DI\env('DB_USERNAME'),
    'pdo.pass' => DI\env('DB_PASSWORD'),
    'pdo.dbname' => DI\env('DB_DATABASE'),
    'pdo.charset' => 'utf8',

    /**
     * Factories
     */
    App\App::class => DI\factory([App\Factory\AppFactory::class, 'create']),
    Monolog\Logger::class => DI\factory([App\Factory\LoggerFactory::class, 'create']),
    PDO::class => DI\factory([App\Factory\PdoFactory::class, 'create']),

    /**
     * Actions
     */
    App\Action\Home\HomeAction::class => DI\autowire(),

    /**
     *  Aliases
     */
    Psr\Log\LoggerInterface::class => DI\get(Monolog\Logger::class),
    Psr\Http\Message\ResponseFactoryInterface::class => DI\create(Laminas\Diactoros\ResponseFactory::class),
];
