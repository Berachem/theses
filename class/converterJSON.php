<?php


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



$json = file_get_contents('extract_theses.json');
$data = json_decode($json, true);


foreach ($data as $key => $value) {
    $langue = $value['langue'] ;
    $title = $value['titres'][$langue];
    $author = $value['auteurs'][0]['nom'].' '.$value['auteurs'][0]['prenom'];
    $date = $value['date_soutenance'];
    $description = getFrenchOrEnglishVersion($value['resumes']);
    $etablissement = $value['etablissements_soutenance'][0]['nom'];
    $oai_set_specs = $value['oai_set_specs'];
    $embargo = $value['embargo'];
    $id = $value['nnt'];
    $link = "https://www.theses.fr/".$id;
    $director = getListNamebyList($value['directeurs_these']);// ARRAY
    $president = sizeof($value['president_jury'])>0 ? array($value['president_jury']['nom']." ".$value['president_jury']['prenom']) : array(); // ARRAY
    $rapportors = getListNamebyList($value['rapporteurs']); // ARRAY
    $members = getListNamebyList($value['membres_jury']); // ARRAY
    $discipline = $value['discipline']['fr'];
    $status = $value['status'];
    $subjects = getFrenchOrEnglishVersion($value['sujets']);

    echo 'description : ';
    echo "id : ".$id."<br>";
    print_r($director);
    echo '<br>';
    print_r($president);
    echo '<br>';
    print_r($rapportors);
    echo '<br>';
    print_r($members);
    echo '<br>';
    print_r($subjects);
    echo '<br>';
    echo "nnt : ".$id ."<br> titre : ".$title."<br> auteur : ".$author."<br> date : ".$date."<br> description : ".$description."<br> etablissement : ".$etablissement."<br> discipline : ".$discipline."<br> status : ".$status."<br><br>";
    echo "<br><br>" ;
}















/*

// connect to mysql database
$connection = mysqli_connect('localhost', 'root', '', 'test');
mysqli_set_charset($connection, 'utf8');

// insert data into mysql database
foreach ($data as $row) {
    $query = "INSERT INTO `test` (`id`, `name`) VALUES ('" . $row['id'] . "', '" . $row['name'] . "')";
    mysqli_query($connection, $query);
}

*/





?>