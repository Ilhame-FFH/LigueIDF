<?php
include('../head.php');

/**
 * Ajout Certificiation
 * administration/ajoutCertification.php
 * @package     administration
 * @subpackage  Categories
 * @author      Ilhame Mouzouri <i.mouzouri@ffhandball.net>
 * @version     v.1.1 (15/05/2021)
 * @copyright   Copyright (c) 2021
 */


/**
 * Calcul de la taille d'un dossier
 * @param <int> $name 
 * @return type $Rep
 */
function TailleDossier($Rep) {
	$Racine = opendir($Rep);
	$Taille = 0;
	while ($Dossier = readdir($Racine)) {
		if ($Dossier != '..' And $Dossier != '.') {
			//Ajoute la taille du fichier
			$Taille += filesize($Rep . '/' . $Dossier);
		}
	}
	closedir($Racine);
	return $Taille;
}

if (isset($_POST["envoyer"])) {
	
	$req = $conn->prepare('INSERT INTO certification(libelle_certification, famille)
				VALUES(:libelle_certification,:famille)');

	$req->execute(array(
		'libelle_certification' => $_POST['libelle_certification'],
		'famille' => $_POST['famille']
	));
	$id_certification = $conn->query('SELECT id_certification FROM certification c WHERE libelle_certification = "' . $_POST["libelle_certification"] . '";')->fetch()['id_certification'];
	var_dump($id_certification);
	$repertoireDestination = 'upload/' . $id_certification . '/';
	if (!is_dir($repertoireDestination)) {
		mkdir($repertoireDestination, 0777, true);
	}
	$taille_max = 10485760; //10Mo =10000000 bytes
	$compteur = count(glob($repertoireDestination . "*.*")); //nb fichiers dans le dossier
	$taille_dossier = TailleDossier($repertoireDestination);
	//ajouter le fichier dans le repertoire /upload/id 
	if (move_uploaded_file($_FILES["monfichier"]['tmp_name'], $repertoireDestination . basename($_FILES["monfichier"]["name"]))) {
		$compteur = count(glob($repertoireDestination . "*.*")); //nb fichiers dans le dossier
		$taille_dossier = TailleDossier($repertoireDestination);
	} else {
		echo "Problème lors du telechargement du fichier";
		//print_r($_FILES);
	}
	header("Location: administration.php");
}
?>

<div class="container">
	<div class="row">

		<!-- Article main content -->
		<article class="col-xs-12 maincontent">

			<h1>Ajouter Formation</h1>
			<form method="POST" enctype="multipart/form-data">

				<div class="form-group">
					<label for="InputLibelleCertification">Libelle Certification</label>
					<input type="text" class="form-control" id="InputLibelleCertification" name="libelle_certification" required>
				</div>
				<div class="form-group">
					<label for="InputFamille">Famille</label>
					<input type="text" class="form-control" id="InputFamille" name="famille" required>
				</div>

				<div class="form-group">
					<label for="InputGrilleCertification">Grille Certification</label>
					<input type="hidden" name="MAX_FILE_SIZE" value="104857600" /> <!-- Limite 100Mo -->
					<input type="file" class="form-control" name="monfichier" id="monfichier" required/>
				</div>

				<button type="submit" class="btn btn-primary" name="envoyer">Ajouter</button>
				<a href="javascript:history.back()">
					<button type="button" class="btn btn-light" name="retour">Retour</button>
				</a>
			</form>
		</article>
	</div>
</div>