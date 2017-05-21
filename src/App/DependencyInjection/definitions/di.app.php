<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use RJ\PronosticApp\Persistence\AbstractPersistenceLayer;
use RJ\PronosticApp\Persistence\PersistenceRedBean\RedBeanPersistenceLayer;
use RJ\PronosticApp\Util\Validation\GeneralValidator;
use RJ\PronosticApp\Util\Validation\ValidatorInterface;
use RJ\PronosticApp\WebResource\Fractal\FractalGenerator;
use RJ\PronosticApp\WebResource\WebResourceGeneratorInterface;
use RJ\PronosticApp\Log\LifecycleLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function DI\get;
use function DI\object;
use function DI\string;

return [
    /* Slim configuration */
    'settings.displayErrorDetails' => true,

    'errorHandler' => DI\object(\RJ\PronosticApp\App\Handlers\ErrorHandler::class)
        ->constructor(DI\get('settings.displayErrorDetails'), get(WebResourceGeneratorInterface::class)),
    'phpErrorHandler' => DI\object(\RJ\PronosticApp\App\Handlers\PhpErrorHandler::class)
        ->constructor(DI\get('settings.displayErrorDetails'), get(WebResourceGeneratorInterface::class)),
    'notFoundHandler' => DI\object(\RJ\PronosticApp\App\Handlers\NotFoundHandler::class),
    'notAllowedHandler' => DI\object(\RJ\PronosticApp\App\Handlers\NotAllowedHandler::class),

    /* Middleware */
    AbstractPersistenceLayer::class => object(RedBeanPersistenceLayer::class),

    /* Data repository */
    'RJ\PronosticApp\Model\Repository\*RepositoryInterface' =>
        object('RJ\PronosticApp\Persistence\PersistenceRedBean\Model\Repository\*Repository'),

    /* Services */
    WebResourceGeneratorInterface::class => object(FractalGenerator::class),
    ValidatorInterface::class => object(GeneralValidator::class),

    /* Event configuration */
    EventDispatcherInterface::class => function (ContainerInterface $c) {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($c->get(LifecycleLogger::class));
        return $dispatcher;
    },

    /* Logger configuration */
    StreamHandler::class => object()->constructor(string('{app.logsDir}/logs.log')),
    'logger.handlers' => [
        get(StreamHandler::class)
    ],
    LoggerInterface::class => function (ContainerInterface $container) {
        $logger = new Logger('logger');
        foreach ($container->get('logger.handlers') as $handlers) {
            $logger->pushHandler($handlers);
        }
        return $logger;
    },
];
