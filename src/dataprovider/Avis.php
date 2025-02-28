<?php

namespace SFabc\dataprovider;

class Avis {
    
    private int $id;
    private int $idProduit;
    private string $user;
    private string $mail;
    private int $note;
    private string $commentaire;
    private string $date;

    public function __construct(
        int $id,
        int $idProduit,
        string $user,
        string $mail,
        int $note,
        string $commentaire,
        string $date
    ) {
        $this->id = $id;
        $this->idProduit = $idProduit;
        $this->user = $user;
        $this->mail = $mail;
        $this->note = $note;
        $this->commentaire = $commentaire;
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getIdProduit() {
        return $this->idProduit;
    }

    public function setIdProduit($idProduit){
        $this->idProduit = $idProduit;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail){
        $this->mail = $mail;
    }

    public function getNote() {
        return $this->note;
    }

    public function setNote($note){
        $this->note = $note;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }

    public function setCommentaire($commentaire){
        $this->commentaire = $commentaire;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'idProduit' => $this->idProduit,
            'user' => $this->user,
            'mail' => $this->mail,
            'note' => $this->note,
            'commentaire' => $this->commentaire,
            'date' => $this->date
        ];
    }
}