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
    private $enligne;
    private $codeRegion;

    function __construct($id, $titre, $auteur, $date, $langue, $description, $etablissement, $oai_set_specs, $embargo, $theseOnWork, $link, $director, $president, $rapportors, $members, $discipline, $status, $subjects, $accessible, $codeReg){
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
        $this->enligne = $accessible;
        $this->codeRegion= $codeReg;
        $this->allMembreJury = array(
            $director,
            $president,
            $rapportors,
            $members
        );
            
        }

    public function fixUTF8($string){
        $string = str_replace("e?", "é", $string);
        $string = str_replace("o?", "ô", $string);
        $string = str_replace("a?", "à", $string);
        $string = str_replace("c?", "ç", $string);
        $string = str_replace("u?", "ù", $string);
        $string = str_replace("i?", "î", $string);
        $string = str_replace("e?", "è", $string);
        $string = str_replace("a?", "â", $string);
        $string = str_replace("u?", "û", $string);
        $string = str_replace("i?", "ï", $string);
        $string = str_replace("o?", "ö", $string);
        $string = str_replace("a?", "ä", $string);
        $string = str_replace("u?", "ü", $string);  
        return $string;
    }

    public function getCodeRegion(){
        return $this->codeRegion;
    }

    public function getEnligne(){
        return $this->enligne;
    }

    public function getId(){
        return $this->id;
    }
    public function getTitre(){
        return $this->fixUTF8($this->titre);
    }
    public function getAuteur(){
        return $this->fixUTF8($this->auteur);
    }
    public function getDate(){
        return $this->date;
    }
    public function getLangue(){
        return $this->langue;
    }
    public function getDescription(){
        return $this->fixUTF8($this->description);
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