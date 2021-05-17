<?php
include('head.php');
require 'vendor\autoload.php';

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

/* Recuperation de l'id session */

$req = 'select * from ligue_idf.session_certif s where id_session=' . $_GET['id'] . ';';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();
foreach ($donnees as $value) {
	
}

/* Recuperation des stagiaires */
$req_stagiaires = 'select * from ligue_idf.stagiaire s where s.session_certif_id = "' . $_GET['id'] . '";';
$result_stagiaires = $conn->prepare($req_stagiaires);
try {
	$result_stagiaires->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$stagiaires = $result_stagiaires->fetchAll();

function lire_csv($nom_fichier, $separateur = ";") {
	$row = 0;
	$donnee = array();
	$f = fopen($nom_fichier, "r");
	$taille = filesize($nom_fichier) + 1;
	while ($donnee = fgetcsv($f, $taille, $separateur)) {
		$result[$row] = $donnee;
		$row++;
	}

	fclose($f);
	return $result;
}

function requete_insert($donnees_csv, $table) {
	$insert = array();
	$i = 0;
	while (list($key, $val) = @each($donnees_csv)) {
		/* On ajoute une valeur vide ' ' en début pour le champs d'auto-incrémentation  s'il existe, sinon enlever cette valeur */
		if ($i > 0) {
			$insert[$i] = "INSERT into " . $table . "(formation, session, statut, nom)" . " VALUES(' ',";
			$insert[$i] .= implode("','", $val);
			$insert[$i] .= "'";
		}$i++;
	}
	return $insert;
}

if (isset($_POST["import"])) {
	$xls_file = $_FILES["import_excel"]["tmp_name"];

	$reader = new Xlsx();
	$spreadsheet = $reader->load($xls_file);
	$loadedSheetNames = $spreadsheet->getSheetNames();
	$writer = new Csv($spreadsheet);

	foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
		$writer->setSheetIndex($sheetIndex);
		$file = $loadedSheetName . '.csv';
		$writer->save($file);
	}
	$file = $file;
	$session_id = $_GET['id'];
	if (file_exists($file)) {
		$tab = file($file);
		//var_dump($tab);
		$donnees = lire_csv($file);
		$size = count($donnees);

		for ($i = 1; $i < $size; $i++) {
			foreach ($donnees[$i] as $donnee) {
				$tab = explode(',', $donnee);

				$query = "INSERT INTO stagiaire (formation, session, statut, nom, prenom, date_naissance, courriel, telephone, comite, club, convoque_certification, date_debut, date_fin,session_certif_id) "
						. "VALUES (:formation, :session, :statut, :nom, :prenom, :date_naissance, :courriel, :telephone, :comite, :club, :convoque_certification, :date_debut, :date_fin, :session_certif_id)";
				$req = $conn->prepare($query);
				$req->execute(array(
					'formation' => $tab[0],
					'session' => $tab[1],
					'statut' => $tab[2],
					'nom' => $tab[3],
					'prenom' => $tab[4],
					'date_naissance' => $tab[5],
					'courriel' => $tab[6],
					'telephone' => $tab[7],
					'comite' => $tab[8],
					'club' => $tab[9],
					'convoque_certification' => $tab[10],
					'date_debut' => $tab[11],
					'date_fin' => $tab[12],
					'session_certif_id' => $session_id
				));
			}
		}
	}
	header("Location: stagiaires.php?id=" . $_GET['id']);
}
?>
<html>
	<body>
		<!-- Fixed navbar -->	
		<div class="navbar navbar-inverse navbar-fixed-top headroom" >
			<div class="container">
				<div class="navbar-header">
					<!-- Button for smallest screens -->
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					<a class="navbar-brand" href="index.html"><img src="assets/images/coq_ffh_.png" width="50" height="50" alt="Ligue IDF"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav pull-right">
						<li class="active"><a href="session.php">Sessions</a></li>
						<li><a class="btn" href="deconnexion.php">DECONNEXION</a></li>
					</ul>
				</div>
			</div>
		</div> 

		<header id="head" class="secondary"></header>

		<div class="container">
			<div class="row">
				<article class="col-xs-12 maincontent">
					<header class="page-header">
						<h1 class="page-title">Liste des stagiaires de la session <?= $value['libelle_session'] ?></h1>
					</header>
					<div class="alert alert-danger" role="alert">
						<h1>Attention</h1> Télécharger le modele .xlsx et avant de l'importer Veuillez vous assurer que le fichier est en .xlsx et qu'il comporte les colonnes " statut, nom,	prenom,	date_naissance,	courriel, telephone, convoque_certification, resultats_certification, commentaires_certification.
						<a href="assets/excel/Modele_excel_stagiaires.xlsx" type="application/msexcel">
							<button type="button" class="btn btn-primary" name="modeleExcel">Telecharger le modele Excel</button>
						</a>
					</div>
					<?php $id=$_GET['id'];?>
					<a href="ajoutStagiaire.php?id=<?= "".$id ?>">
						<button type="button" class="btn btn-primary" name="ajoutStagiaire">Ajouter Stagiaire</button>
					</a>

					
					<br>

					<div class="panel panel-default">
						<div class="panel-heading">Importez les données des stagiaires</div>
						<div class="panel-body">
							<div class="table-responsive">
								<span id="message"></span>
								<form method="post" id="import_excel_form" enctype="multipart/form-data">
									<table class="table">
										<tr>
											<td width="25%" align="right">Selectionne le fichier Excel</td>
											<td width="50%"><input type="file" name="import_excel" /></td>
											<td width="25%"><input type="submit" name="import" id="import" class="btn btn-primary" value="Import" /></td>
										</tr>
									</table>
								</form>
								<br />

							</div>
						</div>
					</div>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

			<br/>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Formation</th>
						<th scope="col">Session</th>
						<th scope="col">Statut</th>
						<th scope="col">Nom</th>
						<th scope="col">Prenom</th>
						<th scope="col">Date de naissance</th>
						<th scope="col">Telephone</th>
						<th scope="col">Courriel</th>
						<th scope="col">Comité</th>
						<th scope="col">Club</th>
						<th scope="col">Convoqué Certification</th>
						<th scope="col">Certification</th>
						<th scope="col">Supprimer</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($stagiaires as $v) { ?>
						<tr>
							<td><?= $v['id_stagiaire'] ?></td>
							<td><?= $v['formation'] ?></td>
							<td><?= $v['session'] ?></td>
							<td><?= $v['statut'] ?></td>
							<td><?= $v['nom'] ?></td>
							<td><?= $v['prenom'] ?></td>
							<td><?= $v['date_naissance'] ?></td>
							<td><?= $v['telephone'] ?></td>
							<td><?= $v['telephone'] ?></td>
							<td><?= $v['comite'] ?></td>
							<td><?= $v['club'] ?></td>
							<td><?= $v['convoque_certification'] ?></td>
							<td><a style="display:inline-block;width:100%;height:100%;" href="fiche_certification.php?id=<?= "" . $v['id_stagiaire'] ?>">Fiche de certificaiton</a></td>
							<td> <form action="session.php" method="POST">
									<!--Bouton suppression d'une rencontre-->
									<input type="submit" class="btn btn-danger" value="Supprimer" name="delete" />
									<input type="hidden" value="<?= $v['id_session'] ?>" name="id" />
								</form></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</article>
	</div>
</div>
</body>
</html>