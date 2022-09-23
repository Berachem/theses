<?php


class These
/*
COMMENTAAIRE A FAIRE

*/
{
    private $title;
    private $author;
    private $date;
    private $description;
    private $id;
    private $link;
    private $supervisor;
    private $discipline;
    private $status;
    private $subjectAborded;


    public function __construct($titre,$nom,$date, $desc, $id, $lien,$superviseurs, $discipline, $status, $sujetsAborde){
        $this->title = $titre;
        $this->author = $nom;
        $this->date = $date;
        $this->description = $desc;
        $this->id = $id;
        $this->link = $lien;
        $this->supervisor = $superviseurs;
        $this->discipline = $discipline;
        $this->status = $status;
        $this->subjectAborded = $sujetsAborde;
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


    // generate all getters and setters
    


}





?>