<?php
return [
    '/' => [
        'controler' => SFabc\controlers\HomeControler::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/contact' => [
        'controler' => SFabc\controlers\ControleurContact::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/a-propos' => [
        'controler' => SFabc\controlers\ControleurInfos::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/articles' => [
        'controler' => SFabc\controlers\ControleurArticles::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/recherche-articles' => [
        'controler' => SFabc\controlers\ControleurRechercheArticles::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    /*'/admin' => [
        'controler' => SFabc\controlers\AdminLoginControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/bienvenue' => [
        'controler' => SFabc\controlers\AdminBienvenue::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/logout' => [
        'controler' => SFabc\controlers\AdminLogoutControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],*/
    '/detail' => [
        'controler' => SFabc\controlers\DetailControler::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => true
    ],
    '/avis' => [
        'controler' => SFabc\controlers\AvisControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => true
    ],/*
    '/gestionarticles' => [
        'controler' => SFabc\controlers\GestionArticlesControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/gestionarticles',
        'requiresArgument' => false
    ],
    '/gestionavis' => [
        'controler' => SFabc\controlers\GestionAvisControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/gestionavis',
        'requiresArgument' => false
    ],*/
];
