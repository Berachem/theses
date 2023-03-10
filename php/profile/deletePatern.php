<?php

/*
id : l'id du patern à supprimer envoyé en POST
*/

require '../lib/parse.env.php';
require_once '../Connexion.php';

session_start();
$idUser = $_SESSION["id"];

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    // Vérifie que le patern appartient bien à l'utilisateur avant de le supprimer
    $result = $db->q(
        "SELECT * FROM T_Alertes WHERE idUser = :idUser AND id = :id",
        array(
            array(':idUser', $idUser),
            array(':id', $id)
        )
    );

    if (count($result) == 1) {
        $result = $db->q(
            "DELETE FROM T_Alertes WHERE id = :id",
            array(
                array(':id', $id)
            )
        );

        header("Location: ../../alertes.php?delete=1");
    } else {
        header("Location: ../../alertes.php?error=1");
    }
} else {
    header("Location: ../../index.php?error=1");
}

?>