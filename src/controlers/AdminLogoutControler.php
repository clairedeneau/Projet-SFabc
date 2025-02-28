<?php

declare(strict_types=1);

namespace SFabc\controlers;

class AdminLogoutControler extends Controler
{
    public function get(string $params): void
    {
        unset($_SESSION["user"]);
        $this->render('logout', []);
    }
}



