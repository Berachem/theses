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
    $subjects = getFrenchOrEnglishVersion($value['sujets']);

    $allMembreJury = array_merge(
        $director,
        $president,
        $rapportors,
        $members
    );
    

    echo '<br>';
    echo "nnt : ".$id ."<br> titre : ".$title."<br> auteur : ".$author."<br> date : ".$date."<br> langue : ".$langue."<br> description : ".$description."<br> etablissement : ".$etablissement."<br> discipline : ".$discipline."<br> status : ".$status."<br> embargo : ".$embargo."<br> these sur travaux : ".$theseOnWork;
    echo '<br>';
    echo 'Tous les membres du jury : '.implode(", ",$allMembreJury)."\n";
    echo "<br><br>" ;

    $r = new Connexion('vwryeacbera.mysql.db', 'vwryeacbera','vwryeacbera', 'Cherine93' );



}


/*
EXEMPLE DE PARCOURS POUR RECHERCHER UN MOTIF

    $a = $r->q(
        "SELECT * FROM galerie WHERE description LIKE :motif", 
		array(
			array('motif','La%',PDO::PARAM_STR),
			)
		);
    foreach($a as $b){
        print_r($b);
        echo '<br>';
    }


*/





?>