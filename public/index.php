<?php
    declare(strict_types=1);

    require_once __DIR__ . '/../vendor/autoload.php';

    session_start();

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $routes = require_once __DIR__ . '/../config/routes.php';

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($url, '/'));
    $basePath = '/' . ($parts[0] ?? '');
    $argument = isset($parts[1]) ? implode('/', array_slice($parts, 1)) : null;

    if (isset($routes[$basePath])) {
        $route = $routes[$basePath];
        $controlerClass = $route['controler'];
        $methods = $route['methods'];
        $redirectRelPath = $route['redirect'] ?? '/';
        $requiresArgument = $route['requiresArgument'];

        $protocol = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $redirect = $protocol . "://" . $_SERVER['HTTP_HOST'] . $redirectRelPath;

        if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
            http_response_code(405);
            header("Location: " . $redirect);
            exit();
        }

        if ($requiresArgument && !$argument) {
            http_response_code(400);
            echo "Erreur : argument manquant pour la route {$basePath}.";
            exit();
        }

        if (!class_exists($controlerClass)) {
            http_response_code(500);
            echo "Erreur : Le contrôleur {$controlerClass} est introuvable.";
            exit();
        }

        try {
            $controler = new $controlerClass($redirect);

            $action = strtolower($_SERVER['REQUEST_METHOD']);
            if (method_exists($controler, $action)) {
                if ($requiresArgument) {
                    $controler->$action($argument);
                } else {
                    $controler->$action("");
                }
            } else {
                http_response_code(405);
                echo "Erreur : Méthode {$action} non définie dans le contrôleur.";
            }
        } catch (Throwable $e) {
            http_response_code(500);
            $err_code = 500;
            $erreur = $e->getMessage();
            require_once __DIR__ . '/../views/error.php';
        }
    } else {
        http_response_code(404);
        $err_code = 404;
        $erreur = "Page inexistante...";
        require_once __DIR__ . '/../views/error.php';
    }

