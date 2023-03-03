<?php

require '../lib/parse.env.php';
require_once '../Connexion.php';

header('Content-type: application/json');
// renvoie success : true si l'email est disponible et false sinon

if (!isset($_POST['email'])) {
	echo json_encode(array('success' => false));
	exit();
}

$allEmails = $db->q("SELECT email FROM T_Comptes");

foreach ($allEmails as $email) {
	if ($email->email == $_POST['email']) {
		echo json_encode(array('success' => false, 'message' => 'Email déjà utilisé', "DEBUGallEmails" => $allEmails));
		exit();
	}
}

echo json_encode(array('success' => true));






?>