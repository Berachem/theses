<?php
require_once 'Connexion.php';


/*

Renvoie une liste de chaines de chaine de caractères
correspondant aux noms prénom des personnes 

*/

function getListNamebyList($list){
    $listName = array();
    foreach ($list as $pers) {
        array_push($listName, $pers["nom"]." ".$pers["prenom"]);
    }
    return $listName;
}

/*

Renvoie une liste avec les versions 'fr' des éléments s'ils 
existent, sinon si les versions 'en' sinon rien ("")

*/
function getFrenchOrEnglishVersion($list){

    if ( array_key_exists("fr",$list)){ // ARRAY
        return  $list["fr"];
    }elseif(array_key_exists('en',$list)){
        return $list['en'];
    }else{
        return '';
    }
}


/*

Fonction qui insère toutes les données relatives aux thèses dans la base de données
dans les tables theses, personnes et fonction

*/

function insertAllData($id, $titre, $auteur, $date, $langue, $description, $etablissement, $oai_set_specs, $embargo, $theseOnWork, $link, $director, $president, $rapportors, $members, $discipline, $status, $subjects){
    
    // CONNEXION A LA BASE DE DONNEES
    // OBLIGATOIRE
    $db = new Connexion(...........,...........,...........,......... );

    $db->q(
        'INSERT INTO theses VALUES ("0", :id, :titre, :auteur, :date, :langue, :description, :etablissement, :oai_set_specs, :embargo, :theseOnWork, :link, :discipline, :status, :subjects)',
		array(
            array(':id', $id),
            array(':titre', $titre),
            array(':auteur', $auteur),
            array(':date', $date),
            array(':langue', $langue),
            array(':description', $description),
            array(':etablissement', $etablissement),
            array(':oai_set_specs', $oai_set_specs[0]),
            array(':embargo', $embargo),
            array(':theseOnWork', $theseOnWork),
            array(':link', $link),
            array(':discipline', $discipline),
            array(':status', $status),
            array(':subjects', $subjects)
			)
		);

    //echo '<br>INSERT INTO theses VALUES ("0","'.$id.'", "'.$titre.'", "'.$auteur.'", "'.$date.'", "'.$langue.'", "'.$description.'", "'.$etablissement.'", "'.$oai_set_specs[0].'", "'.$embargo.'", "'.$theseOnWork.'", "'.$link.'", "'.$discipline.'", "'.$status.'", "'.$subjects.'")<br>';

    $roles = array("director", "president", "rapportor", "member");
    $i = 0;
    $allMembers = array($director, $president, $rapportors, $members);
    foreach ($allMembers as $poste){
        foreach($poste as $pers){
            $db->q(
                "INSERT INTO personnes VALUES (0, :nom)",
                array(
                    array(':nom', $pers)
                )
                );
            
            
            $id_pers = $db->q(
                "SELECT DISTINCT id FROM personnes WHERE :nom LIKE nom",
                array(
                    array(':nom', $pers)
                )
                );
            $id_pers= $id_pers[0]->id;

            //echo '<br> pers : '.$pers.' id : '.$id_pers.'<br>';	

            $db->q(
                "INSERT INTO fonction VALUES (0, :nnt, :id_pers,:fonction)",
                array(
                    array(':nnt', $id),
                    array(':id_pers', $id_pers),
                    array(':fonction', $roles[$i])

                )
            );
        }
        $i++;
    }
}




// Décodage d'un fichier JSON Récupère une chaîne encodée JSON et la convertit en une variable PHP
$json = file_get_contents('extract_theses.json');
$data = json_decode($json, true);

// parcours des thèses
foreach ($data as $key => $value) {
    $langue = $value['langue'] ;
    $title = $value['titres'][$langue];
    $author = $value['auteurs'][0]['nom'].' '.$value['auteurs'][0]['prenom'] ;
    $date = $value['date_soutenance'];
    $description = getFrenchOrEnglishVersion($value['resumes']);
    $etablissement = $value['etablissements_soutenance'][0]['nom'];
    $oai_set_specs = $value['oai_set_specs'];
    $embargo = $value['embargo'];
    $id = $value['nnt'];
    $theseOnWork = $value['these_sur_travaux'];
    $link = "https://www.theses.fr/".$id;
    $director = getListNamebyList($value['directeurs_these']);// ARRAY
    $president = sizeof($value['president_jury'])>0 ? array($value['president_jury']['nom']." ".$value['president_jury']['prenom']) : array(); // ARRAY
    $rapportors = getListNamebyList($value['rapporteurs']); // ARRAY
    $members = getListNamebyList($value['membres_jury']); // ARRAY
    $discipline = $value['discipline']['fr'];
    $status = $value['status'];
    $subjects = implode(', ',getFrenchOrEnglishVersion($value['sujets']));

    $allMembreJury = array_merge(
        $director,
        $president,
        $rapportors,
        $members
    );
    
    /*
    echo '<br>';
    echo "nnt : ".$id ."<br> titre : ".$title."<br> auteur : ".$author."<br> date : ".$date."<br> langue : ".$langue."<br> description : ".$description."<br> etablissement : ".$etablissement."<br> discipline : ".$discipline."<br> status : ".$status."<br> embargo : ".$embargo."<br> these sur travaux : ".$theseOnWork;
    echo '<br>';
    echo 'Tous les membres du jury : '.implode(", ",$allMembreJury)."\n";
    echo "<br><br>" ;
    */
    insertAllData($id,$title,$author,$date,$langue,$description,$etablissement,$oai_set_specs,$embargo,$theseOnWork,$link,$director,$president,$rapportors,$members,$discipline,$status,$subjects);


}


/*
EXEMPLE DE PARCOURS POUR RECHERCHER UN MOTIF

    $a = $r->q(
        "SELECT * FROM galerie WHERE description LIKE :motif", 
		array(
			array('motif','La%'),
			)
		);
    foreach($a as $b){
        print_r($b);
        echo '<br>';
    }


*/





?>