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


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * @param mixed $supervisor
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return mixed
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @param mixed $discipline
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSubjectAborded()
    {
        return $this->subjectAborded;
    }

    /**
     * @param mixed $subjectAborded
     */
    public function setSubjectAborded($subjectAborded)
    {
        $this->subjectAborded = $subjectAborded;
    }


}





?>