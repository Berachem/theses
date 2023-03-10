<?php

/*
                                       <form method="POST" action="php/profile/addPatern.php">
                                            <div class="form-group">
                                                <label for="patern">Patern :</label>
                                                <input type="text" class="form-control" id="patern" name="patern">
                                            </div>
                                            <div class="form-group">
                                                <label for="intervalle">Intervalle :</label>
                                                <select class="form-control" id="intervalle" name="intervalle">
                                                    <option value="1">1 heure</option>
                                                    <option value="6">6 heures</option>
                                                    <option value="12">12 heures</option>
                                                    <option value="24">24 heures</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </form>

et de la table : 


id	idUser ($_SESSION["idUser"])	patern	intervalle
*/

require '../lib/parse.env.php';
require_once '../Connexion.php';


session_start();

if (isset($_POST["patern"]) && isset($_POST["intervalle"]) && isset($_SESSION["id"])) {
    $patern = $_POST["patern"];
    $intervalle = intval($_POST["intervalle"]);
    $idUser = $_SESSION["id"];

    // si il existe deja deux paterns existants pour l'utilisateur
    $result = $db->q(
        "SELECT * FROM T_Alertes WHERE idUser = :idUser",
        array(
            array(':idUser', $idUser)
        )
    );

    if (count($result) > 1) {
        header("Location: ../../alertes.php?full=1");
        exit;
    }


    $result = $db->q(
        "INSERT INTO T_Alertes (idUser, patern, intervalle) VALUES (:idUser, :patern, :intervalle)",
        array(
            array(':idUser', $idUser),
            array(':patern', $patern),
            array(':intervalle', $intervalle)
        )
    );
    header("Location: ../../alertes.php?add=1");

}else{
    header("Location: ../../index.php?error=1");
}



?>