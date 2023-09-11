<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('contact', 'Core:Contact:');
        $router->addRoute('administration', 'Core:Administration:');
        $router->addRoute('<action>', [
            'presenter' => 'Core:Administration',
            'action' => [
                Route::FILTER_STRICT => true,
                Route::FILTER_TABLE => [
                    'administration' => 'default',
                    'login' => 'login',
                    'logout' => 'logout',
                    'register' => 'register'
                ]
            ]
        ]);
        $router->addRoute('<action>[/<url>]', [
            'presenter' => 'Core:Article',
            'action' => [
                Nette\Routing\Route::FILTER_STRICT => true,
                Nette\Routing\Route::FILTER_TABLE =>
                    [
                        'article-list' => 'list',
                        'editor' => 'editor',
                        'remove' => 'remove'
                    ]
            ]
        ]);
        $router->addRoute('[<url>]', 'Core:Article:');
        return $router;
    }
}
