<?php
return [
    '/' => [
        'controler' => SFabc\controlers\Homecontroler::class,
        'methods' => ['GET', 'POST'],
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
    '/search' => [
        'controler' => SFabc\controlers\Searchcontroler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/logout' => [
        'controler' => SFabc\controlers\Logoutcontroler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/me' => [
        'controler' => SFabc\controlers\Profilecontroler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/suscribe' => [
        'controler' => SFabc\controlers\Suscribecontroler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ]
];
