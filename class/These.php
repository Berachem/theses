<?php


class These
/*
COMMENTAAIRE A FAIRE

*/
{
    private $title;
    private $name;
    private $date;
    private $description;
    private $id;
    private $link;
    private $supervisor;


    public function __construct($titre,$nom,$date, $desc, $id, $lien,$superviseurs){
        $this->title = $titre;
        $this->name = $nom;
        $this->date = $date;
        $this->description = $desc;
        $this->id = $id;
        $this->link = $lien;
        $this->supervisor = $superviseurs;
    }

    public function titleORdescriptionContainsString($string){
        if (strpos($this->title, $string) !== false) {
            return true;
        }
        if (strpos($this->description, $string) !== false) {
            return true;
        }
        return false;
    }


    public function getTitle(){
        return $this->title;
    }
    public function getName(){
        return $this->name;
    }
    public function getDate(){
        return $this->date;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getId(){
        return $this->id;
    }
    public function getLink(){
        return $this->link;
    }
    public function getSupervisor(){
        return $this->supervisor;
    }



}





?>