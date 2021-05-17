<?php
include('head.php');
$req = 'select * from ligue_idf.formation f ;';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();
$default="Aucune";

if (isset($_POST["envoyer"])) {
	$req = $conn->prepare('INSERT INTO session(libelle_session, code_session, formation_id)
				VALUES(:libelle_session, :code_session, :formation_id)');
	
	$id_formation = $conn->query('SELECT id_formation from formation where libelle_formation = "' . $_POST['formation'] . '";')->fetch()['id_formation'];

	print_r($id_formation);

	$req->execute(array(
		'libelle_session' => $_POST['libelle_session'],
		'code_session' => $_POST['code_session'],
		'formation_id' => $id_formation
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
					<label for="InputDateRencontre">Libelle Session</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="libelle_session" required>
				</div>
				<div class="form-group">
					<label for="InputHeureRencontre">Code session</label>
					<input type="text" class="form-control" id="InputHeureRencontre" placeholder="" name="code_session" required>
				</div>
				<div class="form-group">
					<label for="InputFormation">Formation associée</label>
					<select name="formation" class="form-control">
						<?php foreach ($donnees as $value) { ?>
							<option value="<?= $value['libelle_formation'] ?>" <?= ($value['libelle_formation'] == $default ? 'selected="selected"' : null) ?>><?= $value['libelle_formation'] ?></option>
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