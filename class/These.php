<?php


/*
 Classe représentant une thèse avec tous ses attributs et ses méthodes
 qui sont des accesseurs principalement.

*/
class These
{
    private $id;
    private $titre;
    private $auteur;
    private $date;
    private $langue;
    private $description;
    private $etablissement;
    private $oai_set_specs;
    private $embargo;
    private $theseOnWork;
    private $link;
    private $director;
    private $president;
    private $rapportors;
    private $members;
    private $discipline;
    private $status;
    private $subjects;
    private $allMembreJury;

    function __construct($id, $titre, $auteur, $date, $langue, $description, $etablissement, $oai_set_specs, $embargo, $theseOnWork, $link, $director, $president, $rapportors, $members, $discipline, $status, $subjects){
        $this->id = $id;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->date = $date;
        $this->langue = $langue;
        $this->description = $description;
        $this->etablissement = $etablissement;
        $this->oai_set_specs = $oai_set_specs;
        $this->embargo = $embargo;
        $this->theseOnWork = $theseOnWork;
        $this->link = $link;
        $this->director = $director;
        $this->president = $president;
        $this->rapportors = $rapportors;
        $this->members = $members;
        $this->discipline = $discipline;
        $this->status = $status;
        $this->subjects = $subjects;
        $this->allMembreJury = array(
            $director,
            $president,
            $rapportors,
            $members
        );
            
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

    public function getId(){
        return $this->id;
    }
    public function getTitre(){
        return $this->titre;
    }
    public function getAuteur(){
        return $this->auteur;
    }
    public function getDate(){
        return $this->date;
    }
    public function getLangue(){
        return $this->langue;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getEtablissement(){
        return $this->etablissement;
    }
    public function getOai_set_specs(){
        return $this->oai_set_specs;
    }
    public function getEmbargo(){
        return $this->embargo;
    }
    public function getTheseOnWork(){
        return $this->theseOnWork;
    }
    public function getLink(){
        return $this->link;
    }
    public function getDirector(){
        return $this->director;
    }
    public function getPresident(){
        return $this->president;
    }
    public function getRapportors(){
        return $this->rapportors;
    }
    public function getMembers(){
        return $this->members;
    }
    public function getDiscipline(){
        return $this->discipline;
    }
    public function getStatus(){
        return $this->status;
    }
    public function getSubjects(){
        return $this->subjects;
    }
    public function getAllMembreJury(){
        return $this->allMembreJury;
    }

}





?>