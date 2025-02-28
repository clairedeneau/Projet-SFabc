<?php
declare(strict_types=1);

namespace SFabc\dataprovider;

class Avis{
    private int $idProduit;
    private string $user;
    private int $note;
    private string $comment;
    private string $date;

    public function __construct(
        int $idProduit,
        string $user,
        int $note,
        string $comment,
        string $date
    )
    {
        $this->idProduit = $idProduit;
        $this->user = $user;
        $this->note = $note;
        $this->comment = $comment;
        $this->date = $date;   
    }

    public function getNote(): int
    {
        return $this->note;
    }
}