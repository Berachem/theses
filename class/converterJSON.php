<?php

function getListNamebyList($list){
    $listName = array();
    foreach ($list as $pers) {
        if (isset($pers->name)) {
            array_push($listName, $pers["nom"]." ".$pers["prenom"]);
        }
        
    }
    return $listName;
}



$json = file_get_contents('extract_theses.json');
$data = json_decode($json, true);

//$r = new Connexion();


foreach ($data as $key => $value) {
    $langue = $value['langue'] ;
    $title = $value['titres'][$langue];
    $author = $value['auteurs'][0]['nom'].' '.$value['auteurs'][0]['prenom'];
    $date = $value['date_soutenance'];

    if ( in_array("fr",$value['resumes'])){
        $description = $value['resumes'][$langue];
    }elseif(in_array("en",$value['resumes'])){
        $description = $value['resumes']["en"];
    }else{
        $description = '';
    }
    $etablissement = $value['etablissements_soutenance'][0]['nom'];
    $oai_set_specs = $value['oai_set_specs'];
    $embargo = $value['embargo'];
    
    $id = $value['nnt'];
    $link = "https://www.theses.fr/".$id;
    $director = getListNamebyList($value['directeurs_these']);// ARRAY
    $president = getListNamebyList($value['president_jury']); // ARRAY
    $rapportors = getListNamebyList($value['rapporteurs']); // ARRAY
    $members = getListNamebyList($value['membres_jury']); // ARRAY
    $discipline = $value['discipline']['fr'];
    $status = $value['status'];


    if ( in_array("fr",$value['sujets'])){ // ARRAY
        $subjects = $value['sujets']['fr'];
    }elseif(in_array("en",$value['sujets'])){
        $subjects = $value['sujets']["en"];
    }else{
        $subjects = '';
    }
    echo "nnt : ".$id ." titre : ".$title." auteur : ".$author." date : ".$date." description : ".$description." etablissement : ".$etablissement."  embargo : ".$embargo." discipline : ".$discipline." status : ".$status." link : ".$link.'<br>';
    print_r($director).'<br>'. print_r($president).'<br>'. print_r($rapportors).'<br>'. print_r($members).'<br>'. print_r($subjects).'<br>';

    echo "<br><br>" ;
}


//$r->q("SELECT * FROM theses");


//print_r($r);















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