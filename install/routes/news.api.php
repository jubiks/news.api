<?php
use Bitrix\Main\Routing\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    if(Bitrix\Main\Loader::includeModule('news.api')) {
        $routes->prefix('api')->group(function (RoutingConfigurator $routes) {
            $routes->get('news.list', [\News\Api::class, 'listAction']);
            $routes->get('news.detail', [\News\Api::class, 'detailAction']);
        });
    }
};