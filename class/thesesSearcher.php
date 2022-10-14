<?php

/*

Classe qui renvoie la liste des thèses en recherchant dans la base de données Un motif
dans les champs titre, auteur, description, etablissement, langue, discipline, status, sujets...

*/



/*
Fonction qui renvoie une note attribuée à une thèse en fonction de ses attributs contenant
le motif de recherché
*/
function getMarkOnThese($these, $motif){
    $mark = 1;
    if (strpos($these->getTitre(), $motif) != false) {
        $mark += 10;
    }
    if (strpos($these->getSubjects(), $motif) != false) {
        $mark += 5;
    }
    if (strpos($these->getAuteur(), $motif) != false) {
        $mark += 5;
    }
    if (strpos($these->getDescription(), $motif) != false) {
        $mark += 4;
    }
    return $mark;
}


/*
Fonction qui renvoie la liste des thèses en recherchant dans la base de données Un motif
*/
function getAllThesesByAttributes($db, $motif){
        // On recherche tous les thèses qui correspondent à la recherche
        $theses = array();
        $researchThesesDB = $db->q(
            "SELECT * FROM theses WHERE nnt LIKE :search OR titres LIKE :search OR auteurs LIKE :search OR etablissements_soutenance LIKE :search OR resume LIKE :search OR sujets LIKE :search OR discipline LIKE :search",
            array(
                array(':search', '%'.$motif.'%')
            )
     
        );
        //echo "ici : <br>";
        //print_r($researchThesesDB);
        
        
    
        // On récupère les noms de tous les membres du jury
        foreach($researchThesesDB as $these){
    
            $researchAllMembersDB = $db->q(
                "SELECT * FROM fonction f, personnes p  WHERE f.idpers = p.id AND f.nnt = :nnt",
                array(
                    array(':nnt', $these->nnt)
                )
            );
    
        
        //echo "iciiiiiiiiiii : <br>";
        //print_r($researchAllMembersDB);
    
        // On les place dans les bonnes listes coorespondant à leur rôle
        $directors = array();
        $presidents = array();
        $rapportors = array();
        $members = array();
        foreach ($researchAllMembersDB as $member) {
            if ($member->fonction == "director") {
                array_push($directors, $member);
            }
            if ($member->fonction == "president") {
                array_push($presidents, $member);
            }
            if ($member->fonction == "rapportor") {
                array_push($rapportors, $member);
            }
            if ($member->fonction == "member") {
                array_push($members, $member);
            }
        }
    
        // On caste les résultats en objets These
            $these = new These(
                $these->nnt,
                $these->titres,
                $these->auteurs,
                $these->date_soutenance,
                $these->langue,
                $these->resume,
                $these->etablissements_soutenance,
                $these->oai_set_specs,
                $these->embargo,
                $these->theseOnWork,
                $these->link,
                $directors,
                $presidents,
                $rapportors,
                $members,
                $these->discipline,
                $these->status,
                $these->sujets,
                $these->accessible
            );
            array_push($theses, array($these, getMarkOnThese($these, $motif)));
            //array_push($theses, $these);
            // on ajoute la these avec sa note liée en fonction de sa pertinence
            //$theses[$these] = getMarkOnThese($these, $motif);

            //print_r($theses);


            
        }
        //print_r($theses);
        //exit();
        arsort($theses);
        //return array_keys($theses);
        return array_map(function($these) { return $these[0]; }, $theses);
        //return $theses;
}

?>