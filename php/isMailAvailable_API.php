<?php
require_once 'Connexion.php';


// renvoie success : true si l'email est disponible et false sinon

$allEmails = $db->q("SELECT email FROM T_Comptes");

foreach ($allEmails as $email) {
	if ($email == $_POST['email']) {
		header('Content-type: application/json', true);
		echo json_encode(array('success' => false));
		exit();
	}
}
header('Content-type: application/json', true);
echo json_encode(array('success' => true));






?>