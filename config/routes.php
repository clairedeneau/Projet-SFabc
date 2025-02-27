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
    '/admin' => [
        'controler' => SFabc\controlers\AdminLoginControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/logout' => [
        'controler' => SFabc\controlers\AdminLogoutControler::class,
        'methods' => ['GET', 'POST'],
        'redirect' => '/',
        'requiresArgument' => false
    ],
    '/detail' => [
        'controler' => SFabc\controlers\DetailControler::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => true
    ],
    '/avis' => [
        'controler' => SFabc\controlers\AvisControler::class,
        'methods' => ['GET'],
        'redirect' => '/',
        'requiresArgument' => false
    ]
];
