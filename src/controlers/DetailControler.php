<?php

declare(strict_types=1);

namespace SFabc\controlers;

class DetailControler extends Controler
{
    public function get(string $params): void
    {
        $this->render('detail', []);
    }
}