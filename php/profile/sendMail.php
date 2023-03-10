<?php



require_once '../These.php';
require '../lib/parse.env.php';
require_once '../Connexion.php';
require_once '../thesesSearcher.php';


function getPseudoFromId($id) {
    global $db;
    $result = $db->q(
        "SELECT pseudo FROM T_Comptes WHERE id = :id",
        array(
            array(':id', $id)
        )
    );
    return $result[0]->pseudo;
}

function getMailFromId($id) {
    global $db;
    $result = $db->q(
        "SELECT email FROM T_Comptes WHERE id = :id",
        array(
            array(':id', $id)
        )
    );
    return $result[0]->email;
}

function getPaternFromIdPatern( $idPatern, $idUser) {
    global $db;
    $result = $db->q(
        "SELECT patern FROM T_Alertes WHERE id = :idPatern AND idUser = :idUser",
        array(
            array(':idPatern', $idPatern),
            array(':idUser', $idUser)
        )
    );

    return $result[0]->patern;
}


/*

curl --request POST \
--url https://api.courier.com/send \
--header 'Authorization: Bearer pk_prod_7JZT2XRJTPMXG2KPQ4QKYFJCXP8A' \
--data '{
    "message": {
      "to": {"email":"berachem.markria@gmail.com"},
      "template": "HXEY75YS6SMCKWM48R7V7DYBGZ0Q",
      "brand_id": "FFFWPJ6D51MWBAMQS866H52F9YSV",
      "data": {"nom":"nom","motif":"motif","tableauTheses":"tableauTheses"}
    }
}'
*/

function sendMailWithPatern($idUser, $idPatern) {
    global $db;

    $patern = getPaternFromIdPatern($idPatern, $idUser);

    $theses = getAllThesesByAttributes($db, $patern);

    // keep only the first 5 theses
    //$theses = getThe10MostReccurentSubjects($theses);
    $theses = array_slice($theses, 0, 5);



    $nom = getPseudoFromId($idUser);
    $email = getMailFromId($idUser);
    $motif = $patern;
    $tableauTheses = "";
    foreach ($theses as $index => $these) {
        $tableauTheses .= ($index + 1) . ". " . $these->getTitre() . " (" . $these->getAuteur() . ")\n";
    }

    // Set up request data
    $data = array(
        'message' => array(
            'to' => array('email' => $email),
            'template' => 'HXEY75YS6SMCKWM48R7V7DYBGZ0Q',
            'brand_id' => 'FFFWPJ6D51MWBAMQS866H52F9YSV',
            'data' => array(
                'nom' => $nom,
                'motif' => $motif,
                'resultat' => $tableauTheses
            )
        )
    );

    $data = json_encode($data);

    // Set up cURL request
    $ch = curl_init('https://api.courier.com/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer pk_prod_7JZT2XRJTPMXG2KPQ4QKYFJCXP8A',
        'Content-Type: application/json'
    ));

    // Execute cURL request
    $response = curl_exec($ch);
    curl_close($ch);

}


session_start();
if (isset($_SESSION["id"]) && isset($_GET["idPatern"])) {
    $idUser = $_SESSION["id"];
    $idPatern = intval($_GET["idPatern"]);
   /*  echo "DEBUG: idUser = $idUser, idPatern = $idPatern";
    exit; */
    sendMailWithPatern($idUser, $idPatern);
    header("Location: ../../alertes.php?try=1");
} else {
    header("Location: ../../index.php?error=1");
}

