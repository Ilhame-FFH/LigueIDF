<?php
include('../head.php');
require '../vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * Stagiaires
 * coordinateur/stagiaires.php
 * @package     
 * @subpackage  
 * @author      Ilhame Mouzouri i.mouzouri@ffhandball.net
 * @version     v.1.1 (15/05/2021)
 * @copyright   Copyright (c) 2021
 */

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

/* Recuperation des jurys */
$req_stagiaires = 'select * from ligue_idf.jury j where j.session_certif_id = "' . $_GET['id'] . '";';
$result_stagiaires = $conn->prepare($req_stagiaires);
try {
	$result_stagiaires->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$stagiaires = $result_stagiaires->fetchAll();


/**
 * Lire fichier CSV 
 * @param String $nom_fichier 
 * @param String $separateur
 * @return Object $conn
 */
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

/**
 * Fonction connexion à la base
 * focntions.php
 * @package     
 * @subpackage  
 * @author      Ilhame Mouzouri <i.mouzouri@ffhandball.net>
 * @version     v.1.1 (15/05/2021)
 * @copyright   Copyright (c) 2021
 */
/**
 * Requete insertion bd 
 * @param Object $donnees_csv 
 * @param String $table
 * @return Object $insert
 */
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

				$query = "INSERT INTO jury (statut, nom, prenom, volume_horaire, fonction, diplome, session_certif_id) "
						. "VALUES (:statut, :nom, :prenom, :volume_horaire, :fonction, :diplome, :session_certif_id)";
				$req = $conn->prepare($query);
				$req->execute(array(
					'statut' => $tab[0],
					'nom' => $tab[1],
					'prenom' => $tab[2],
					'volume_horaire' => $tab[3],
					'fonction' => $tab[4],
					'diplome' => $tab[5],
					'session_certif_id' => $session_id
				));
			}
		}
	}
	header("Location: jury.php?id=" . $_GET['id']);
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
					<a class="navbar-brand" href="index.html"><img src="/LigueIDF/assets/images/coq_ffh_.png" width="50" height="50" alt="Ligue IDF"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav pull-right">
						<li ><a href="session.php">Sessions</a></li>
						<li ><a href="stagiaires.php?id=<?= "".$_GET['id'] ?>">Stagiaires</a></li>
						<li class="active"><a href="jury.php?id=<?= "".$_GET['id'] ?>">Jurys</a></li>
						<li><a href="examens.php?id=<?= "".$_GET['id'] ?>">Examens</a></li>
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
						<h1 class="page-title">Liste des jurys de la session <?= $value['libelle_session'] ?></h1>
					</header>
					<div class="alert alert-danger" role="alert">
						<h1>Attention</h1> Télécharger le modele .xlsx et avant de l'importer Veuillez vous assurer que le fichier est en .xlsx et qu'il comporte les colonnes " statut, nom,	prenom,	date_naissance,	courriel, telephone, convoque_certification, resultats_certification, commentaires_certification.
						<a href="/Ligue_IDF-master/LigueIDF/assets/excel/Modele_excel_jurys.xlsx" type="application/msexcel">
							<button type="button" class="btn btn-primary" name="modeleExcel">Telecharger le modele Excel</button>
						</a>
					</div>
					<?php $id=$_GET['id'];?>
					<a href="ajoutStagiaire.php?id=<?= "".$id ?>">
						<button type="button" class="btn btn-primary" name="ajoutStagiaire">Ajouter Jury</button>
					</a>

					
					<br>

					<div class="panel panel-default">
						<div class="panel-heading">Importez les données des jurys</div>
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
						<th scope="col">Statut</th>
						<th scope="col">Nom</th>
						<th scope="col">Prenom</th>
						<th scope="col">Volume Horaire</th>
						<th scope="col">Fonction</th>
						<th scope="col">Diplome</th>
						<th scope="col">Supprimer</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($stagiaires as $v) { ?>
						<tr>
							<td><?= $v['id_jury'] ?></td>
							<td><?= $v['statut'] ?></td>
							<td><?= $v['nom'] ?></td>
							<td><?= $v['prenom'] ?></td>
							<td><?= $v['volume_horaire'] ?></td>
							<td><?= $v['fonction'] ?></td>
							<td><?= $v['diplome'] ?></td>
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