<?php
declare(strict_types=1);

namespace SFabc\controlers;

class AdminBienvenue extends Controler
{
    public function get(string $params): void
    {
        $this->render('/admin/bienvenue', []);
    }
}
