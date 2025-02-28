<?php
    declare(strict_types=1);
    namespace SFabc\controlers;
    abstract class Controler
    {
        protected string $redirect;
        public function __construct(string $redirect)
        {
            $this->redirect = $redirect;
        }
        protected function redirect(): void
        {
            header("Location: " . $this->redirect);
            exit();
        }
        protected function redirectTo(string $location): void
        {
            $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
            header("Location: " . $protocol . "://" . $_SERVER['HTTP_HOST'] . (str_starts_with($location, "/") ? "" : "/") . $location);
            exit();
        }
        protected function render(string $view, array $data = []): void
        {
            extract($data);
            require_once __DIR__ . '/../../views/' . $view . '.php';
        }
        public function get(string $param): void
        {
            $this->redirectTo("/");
        }
        public function post(string $param): void
        {
            $this->redirectTo("/");
        }
    }