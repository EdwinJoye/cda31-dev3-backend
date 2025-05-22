<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/doc.json' => [[['_route' => 'app.swagger', '_controller' => 'nelmio_api_doc.controller.swagger'], null, ['GET' => 0], null, false, false, null]],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\AuthController::register'], null, null, null, false, false, null]],
        '/all/collaborators' => [[['_route' => 'app_all_collaborator', '_controller' => 'App\\Controller\\CollaboratorController::allCollaborator'], null, ['GET' => 0], null, false, false, null]],
        '/collaborator/id' => [[['_route' => 'app_collaborator_id', '_controller' => 'App\\Controller\\CollaboratorController::collaboratorById'], null, ['POST' => 0], null, false, false, null]],
        '/collaborator/email' => [[['_route' => 'app_collaborator_email', '_controller' => 'App\\Controller\\CollaboratorController::collaboratorByEmail'], null, ['POST' => 0], null, false, false, null]],
        '/collaborator/random' => [[['_route' => 'app_collaborator_random', '_controller' => 'App\\Controller\\CollaboratorController::randomCollaborator'], null, ['GET' => 0], null, false, false, null]],
        '/collaborators/category' => [[['_route' => 'app_collaborator_by_category', '_controller' => 'App\\Controller\\CollaboratorController::collaboratorsByCategory'], null, ['POST' => 0], null, false, false, null]],
        '/collaborators/name' => [[['_route' => 'app_collaborator_by_name', '_controller' => 'App\\Controller\\CollaboratorController::collaboratorsByName'], null, ['POST' => 0], null, false, false, null]],
        '/collaborators/filter' => [[['_route' => 'app_collaborator_by_text', '_controller' => 'App\\Controller\\CollaboratorController::collaboratorsByText'], null, ['POST' => 0], null, false, false, null]],
        '/collaborator/create' => [[['_route' => 'app_collaborator_create', '_controller' => 'App\\Controller\\CollaboratorController::createCollaborator'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/collaborator/(?'
                    .'|update/([^/]++)(*:74)'
                    .'|delete/([^/]++)(*:96)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        74 => [[['_route' => 'app_collaborator_update', '_controller' => 'App\\Controller\\CollaboratorController::updateCollaborator'], ['id'], ['PUT' => 0], null, false, true, null]],
        96 => [
            [['_route' => 'app_collaborator_delete', '_controller' => 'App\\Controller\\CollaboratorController::deleteCollaborator'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
