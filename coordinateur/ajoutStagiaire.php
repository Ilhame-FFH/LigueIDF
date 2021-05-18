<?php

include('../head.php');

if (isset($_POST["envoyer"])) {
	$req = $conn->prepare("INSERT INTO stagiaire (formation, session, statut, nom, prenom, date_naissance, courriel, telephone, comite, club, convoque_certification, date_debut, date_fin,session_certif_id) "
			. "VALUES (:formation, :session, :statut, :nom, :prenom, :date_naissance, :courriel, :telephone, :comite, :club, :convoque_certification, :date_debut, :date_fin, :session_certif_id)");

	$session_id = $_GET['id'];

	$req->execute(array(
		'formation' => $_POST['formation'],
		'session' => $_POST['session'],
		'statut' => $_POST['statut'],
		'nom' => $_POST['nom'],
		'prenom' => $_POST['prenom'],
		'date_naissance' => $_POST['date_naissance'],
		'courriel' => $_POST['courriel'],
		'telephone' => $_POST['telephone'],
		'comite' => $_POST['comite'],
		'club' => $_POST['club'],
		'convoque_certification' => $_POST['convoque'],
		'date_debut' => $_POST['date_debut'],
		'date_fin' => $_POST['date_fin'],
		'session_certif_id' => $session_id
	));
	$id=$_GET['id'];
	header("Location: stagiaires.php?id=$id");
}
?>
<div class="container">

	<div class="row">

		<!-- Article main content -->
		<article class="col-xs-12 maincontent">
			<h1>Ajouter Stagiaire</h1>
			<form method="POST">
				<div class="form-group">
					<label for="InputFormation">Formation</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="formation" required>
				</div>
				<div class="form-group">
					<label for="InputSession">Session</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="session" required>
				</div>
				<div class="form-group">
					<label for="InputCertif">Statut</label>
					<select class="form-control" id="exampleFormControlSelect1" name="statut" required>
						<option>M.</option>
						<option>Mme.</option>
					</select>
				</div>
				<div class="form-group">
					<label for="InputNom">Nom</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="nom" required>
				</div>
				<div class="form-group">
					<label for="InputLibelle">Prenom</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="prenom" required>
				</div>
				<div class="form-group">
					<label for="InputDN">Date de naissance</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="date_naissance" required>
				</div>
				<div class="form-group">
					<label for="InputCourriel">Courriel</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="courriel" required>
				</div>
				<div class="form-group">
					<label for="InputTelephone">Telephone</label>
					<input type="number" class="form-control" id="InputDateRencontre" name="telephone" required>
				</div>
				<div class="form-group">
					<label for="InputComite">Comité</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="comite" required>
				</div>
				<div class="form-group">
					<label for="InputClub">Club</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="club" required>
				</div>
				<div class="form-group">
					<label for="InputCertif">Convoqué à la certification (OUI=1 / NON=0)</label>
					<select class="form-control" id="exampleFormControlSelect1" name="convoque">
						<option>0</option>
						<option>1</option>
					</select>
				</div>
				<div class="form-group">
					<label for="InputDate_debut">Date de debut</label>
					<input type="number" class="form-control" id="InputDateRencontre" name="date_debut" required>
				</div>
				<div class="form-group">					
					<label for="InputDate_fin">Date de fin</label>
					<input type="number" class="form-control" id="InputDateRencontre" name="date_fin" required>
				</div>
				<button type="submit" class="btn btn-primary" name="envoyer">Ajouter</button>
				<a href="javascript:history.back()">
					<button type="button" class="btn btn-light">Retour</button>
				</a>
			</form>
		</article>
	</div>
</div>