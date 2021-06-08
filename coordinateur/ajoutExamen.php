<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('../head.php');
?>

<div class="container">

	<div class="row">

		<!-- Article main content -->
		<article class="col-xs-12 maincontent">
			<h1>Association Stagiaire/Jury</h1>
			<form method="POST">
				<div class="form-group">
					<label for="InputNom">Nom</label>
					<select name="nom" class="form-control">
						<?php foreach ($donnees_saisons as $value) { ?>
							<option value="<?= $value['saison'] ?>" <?= ($value['saison'] == $default ? 'selected="selected"' : null) ?>><?= $value['saison'] ?></option>
						<?php } ?>
					</select>				
				</div>

				<div class="form-group">
					<label for="InputSaison">Prenom</label>
					<input type="text" class="form-control" id="InputDateRencontre" name="libelle_session" disabled>	
				</div>
				<div class="form-group">
					<label for="InputCertification">Jury 1</label>
					<select name="certification" class="form-control">
						<?php foreach ($donnees_certif as $value) { ?>
							<option value="<?= $value['libelle_certification'] ?>" <?= ($value['libelle_certification'] == $default ? 'selected="selected"' : null) ?>><?= $value['libelle_certification'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="InputCertification">Jury 2</label>
					<select name="certification" class="form-control">
						<?php foreach ($donnees_certif as $value) { ?>
							<option value="<?= $value['libelle_certification'] ?>" <?= ($value['libelle_certification'] == $default ? 'selected="selected"' : null) ?>><?= $value['libelle_certification'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="InputDateExamen">Date d'Examen</label>
					<input type="date" class="form-control" id="InputDateExamen" name="date_examen" required>
				</div>
				<button type="submit" class="btn btn-primary" name="envoyer">Ajouter</button>
				<a href="javascript:history.back()">
					<button type="button" class="btn btn-light">Retour</button>
				</a>
			</form>
		</article>
	</div>
</div>