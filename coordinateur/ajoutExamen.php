<?php
/**
 * Ajout Examen
 * coordinateur/ajoutExamen.php
 * @package     coordinateur
 * @subpackage  Categories
 * @author      Ilhame Mouzouri <i.mouzouri@ffhandball.net>
 * @version     v.1.1 (15/06/2021)
 * @copyright   Copyright (c) 2021
 */

include('../head.php');

#recuperation nom/prenom jury
$req = 'select nom, prenom from ligue_idf.jury j where session_certif_id=' . $_GET['id'] . ';';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();


#recuperation nom stagiaire
$req2 = 'select nom, prenom from ligue_idf.stagiaire s where session_certif_id=' . $_GET['id'] . ';';
$result2 = $conn->prepare($req2);
try {
	$result2->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees2 = $result2->fetchAll();

#update stagiaire
if (isset($_POST["envoyer"])) {

	$PDOStatement = $conn->prepare("UPDATE stagiaire SET jury1_id = :jury1_id, jury2_id = :jury2_id, date_examen = :date_examen, horaire_examen = :horaire_examen WHERE nom = :nom AND session_certif_id=". $_GET['id'] . ';');
	
	$jury1_id = $conn->query('SELECT id_jury from jury where nom = "' . $_POST['jury1_id'] . '";')->fetch()['id_jury'];
	$jury2_id = $conn->query('SELECT id_jury from jury where nom = "' . $_POST['jury2_id'] . '";')->fetch()['id_jury'];

	$PDOStatement->execute(array(
		'jury1_id' => $jury1_id,
		'jury2_id' => $jury2_id,
		'date_examen' => $_POST['date_examen'],
		'horaire_examen' => $_POST['horaire_examen'],
		'nom' => $_POST['nom']));
	$id=$_GET['id'];
	header("Location: examens.php?id=$id");
}
?>
<!-- Formulaire d'ajout d'un examen (association jury/stagiaire)-->
<div class="container">
	<div class="row">
		<!-- Article main content -->
		<article class="col-xs-12 maincontent">
			<h1>Association Stagiaire/Jury</h1>
			<form method="POST">
				<div class="form-group">
					<label for="InputNom">Nom</label>
					<select name="nom" class="form-control">
						<?php foreach ($donnees2 as $value) { ?>
							<option value="<?= $value['nom'] ?>" <?= ($value['nom'] == "aucun" ? 'selected="selected"' : null) ?>><?= $value['nom'] ?></option>
						<?php } ?>
					</select>				
				</div>

				<div class="form-group">
					<label for="InputSaison">Prenom</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="prenom" disabled>	
				</div>
				<div class="form-group">
					<label for="InputCertification">Jury 1</label>
					<select name="jury1_id" class="form-control">
						<?php foreach ($donnees as $value) { ?>
							<option value="<?= $value['nom'] ?>" <?= ($value['nom'] == "aucun" ? 'selected="selected"' : null) ?>><?= $value['nom'] ?></option>
						<?php } ?>
					</select>				

				</div>
				<div class="form-group">
					<label for="InputCertification">Jury 2</label>
					<select name="jury2_id" class="form-control">
						<?php foreach ($donnees as $value) { ?>
							<option value="<?= $value['nom'] ?>" <?= ($value['nom'] == "aucun" ? 'selected="selected"' : null) ?>><?= $value['nom'] ?></option>
						<?php } ?>
					</select>				
				</div>
				<div class="form-group">
					<label for="InputDateExamen">Date d'Examen</label>
					<input type="date" class="form-control" id="InputDateExamen" name="date_examen" required>
				</div>
				<div class="form-group">
					<label for="InputDateExamen">Horaire d'Examen</label>
					<input type="time" class="form-control" id="InputDateExamen" name="horaire_examen" required>
				</div>
				<button type="submit" class="btn btn-primary" name="envoyer">Ajouter</button>
				<a href="javascript:history.back()">
					<button type="button" class="btn btn-light">Retour</button>
				</a>
			</form>
		</article>
	</div>
</div>
