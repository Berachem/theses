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
    private $theme;
    private $status;
    private $subjectsAborded;


    public function __construct($titre,$nom,$date, $desc, $id, $lien,$superviseurs, $discipline, $status, $sujetsAborde){
        $this->title = $titre;
        $this->name = $nom;
        $this->date = $date;
        $this->description = $desc;
        $this->id = $id;
        $this->link = $lien;
        $this->supervisor = $superviseurs;
        $this->theme = $discipline;
        $this->status = $status;
        $this->subjectsAborded = $sujetsAborde;
    }

    public function titleORdescriptionORContainsString($string){
        if (strpos($this->title, $string) !== false) {
            return true;
        }
        if (strpos($this->description, $string) !== false) {
            return true;
        }
        return false;
    }

    public function subjectsAbordedListContainsString($string){
        foreach ($this->subjectsAborded as $subject) {
            if (strpos($subject, $string) !== false) {
                return true;
            }
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
    public function getTheme(){
        return $this->theme;
    }
    public function hasBeenSupported(){
        return $this->status;
    }


}





?>