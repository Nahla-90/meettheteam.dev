<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/colleague' => [[['_route' => 'colleague_index', '_controller' => 'App\\Controller\\ColleagueController::index'], null, ['GET' => 0], null, true, false, null]],
        '/colleague/new' => [[['_route' => 'colleague_new', '_controller' => 'App\\Controller\\ColleagueController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/' => [[['_route' => 'login', '_controller' => 'App\\Controller\\SecurityController::login'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/confirmOtp' => [[['_route' => 'confirmOtp', '_controller' => 'App\\Controller\\SecurityController::confirmOtp'], null, ['POST' => 0], null, false, false, null]],
        '/logout' => [[['_route' => 'logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/colleague/([^/]++)(?'
                    .'|(*:29)'
                    .'|/edit(*:41)'
                    .'|(*:48)'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        29 => [[['_route' => 'colleague_show', '_controller' => 'App\\Controller\\ColleagueController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        41 => [[['_route' => 'colleague_edit', '_controller' => 'App\\Controller\\ColleagueController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        48 => [
            [['_route' => 'colleague_delete', '_controller' => 'App\\Controller\\ColleagueController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
