<?php

declare(strict_types=1);

namespace SFabc\controlers;

class ControleurInfos extends Controler
{
    public function get(string $params): void
    {
        $this->render('a-propos', []);
    }
}