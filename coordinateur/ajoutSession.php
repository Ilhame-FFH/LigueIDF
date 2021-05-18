<?php

include('../head.php');
$req = 'select * from ligue_idf.certification c ;';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees_certif = $result->fetchAll();
$default="Aucune";

$req = 'select * from ligue_idf.saison s ;';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees_saisons = $result->fetchAll();

if (isset($_POST["envoyer"])) {
	$req = $conn->prepare('INSERT INTO session_certif(libelle_session, certification_id, saison_id)
				VALUES(:libelle_session, :certification_id, :saison_id)');
	
	$id_certification = $conn->query('SELECT id_certification from certification where libelle_certification = "' . $_POST['certification'] . '";')->fetch()['id_certification'];
	$id_saison = $conn->query('SELECT id_saison from saison where saison = "' . $_POST['saison'] . '";')->fetch()['id_saison'];


	$req->execute(array(
		'libelle_session' => $_POST['libelle_session'],
		'certification_id' => $id_certification,
		'saison_id' => $id_saison
	));
	header("Location: session.php");
}
?>
<div class="container">

	<div class="row">

		<!-- Article main content -->
		<article class="col-xs-12 maincontent">
			<h1>Ajouter Session</h1>
			<form method="POST">
				<div class="form-group">
					<label for="InputLibelle">Libelle Session</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="libelle_session" required>
				</div>

				<div class="form-group">
					<label for="InputSaison">Saison associée</label>
					<select name="saison" class="form-control">
						<?php foreach ($donnees_saisons as $value) { ?>
							<option value="<?= $value['saison'] ?>" <?= ($value['saison'] == $default ? 'selected="selected"' : null) ?>><?= $value['saison'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="InputCertification">Certification associée</label>
					<select name="certification" class="form-control">
						<?php foreach ($donnees_certif as $value) { ?>
							<option value="<?= $value['libelle_certification'] ?>" <?= ($value['libelle_certification'] == $default ? 'selected="selected"' : null) ?>><?= $value['libelle_certification'] ?></option>
						<?php } ?>
					</select>
				</div>
				
				<button type="submit" class="btn btn-primary" name="envoyer">Ajouter</button>
				<a href="javascript:history.back()">
					<button type="button" class="btn btn-light">Retour</button>
				</a>
			</form>
		</article>
	</div>
</div>