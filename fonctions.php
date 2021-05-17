<?php

/* Fonction connexion à la base */

function connectionBD($dbname = 'ligue_idf') {
	/* Connexion BDD */
	$servername = 'localhost';
	$username = 'root';
	$password = '';

	/* On essaye de se connecter */
	try {
		$conn = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname . '', $username, $password);
		//On définit le mode d'erreur de PDO sur Exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Erreur : " . $e->getMessage();
	}

	return $conn;
}


?>