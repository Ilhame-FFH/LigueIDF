<?php
include('../head.php');
require '../vendor\autoload.php';

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * Examens
 * coordinateur/examens.php
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

/* Recuperation des stagiaires */
$req_stagiaires = 'select s.nom, s.prenom, s.id_stagiaire,j.statut, j.nom as Jnom, s.date_examen, s.horaire_examen from ligue_idf.jury j, stagiaire s where j.id_jury=s.jury1_id and j.session_certif_id = "' . $_GET['id'] . '";';
$result_stagiaires = $conn->prepare($req_stagiaires);
try {
	$result_stagiaires->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$stagiaires = $result_stagiaires->fetchAll();

/* Recuperation du jury 1 */
$req_jury1 = 'select j.nom from ligue_idf.jury j, stagiaire s where j.id_jury=s.jury1_id and j.session_certif_id = "' . $_GET['id'] . '";';
$result_jury1 = $conn->prepare($req_jury1);
try {
	$result_jury1->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$jury1 = $result_jury1->fetchAll();
foreach ($jury1 as $value2) {
	
}
/* Recuperation du jury 2 */
$req_jury2 = 'select * from ligue_idf.jury j, stagiaire s where j.id_jury=s.jury2_id and j.session_certif_id = "' . $_GET['id'] . '";';
$result_jury2 = $conn->prepare($req_jury2);
try {
	$result_jury2->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$jury2 = $result_jury2->fetchAll();

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
						<li><a href="session.php">Sessions</a></li>
						<li><a href="stagiaires.php?id=<?= "".$_GET['id'] ?>">Stagiaires</a></li>
						<li><a href="jury.php?id=<?= "".$_GET['id'] ?>">Jurys</a></li>
						<li class="active"><a href="examens.php?id=<?= "".$_GET['id'] ?>">Examens</a></li>
						<li><a class="btn" href="../deconnexion.php">DECONNEXION</a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<header id="head" class="secondary"></header>

		<div class="container">
			<div class="row">
				<article class="col-xs-12 maincontent">
					<header class="page-header">
						<h1 class="page-title">Liste des examens de la session <?= $value['libelle_session'] ?></h1>
					</header>
					
					<?php $id=$_GET['id'];?>
					<a href="ajoutExamen.php?id=<?= "".$id ?>">
						<button type="button" class="btn btn-primary" name="ajoutExamen">Ajouter Association </button>
						<button type="button" class="btn btn-primary" name="">Exporter </button>

					</a>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

			<br/>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nom</th>
						<th scope="col">Prenom</th>
						<th scope="col">Jury 1</th>
						<th scope="col">Jury 2</th>
						<th scope="col">Date Examen</th>
						<th scope="col">Horaire Examen</th>
						<th scope="col">Adresse</th>
						<th scope="col">Lieu</th>
						<th scope="col">Consigne jury</th>
						<th scope="col">Consigne Stagiaire</th>
						<th scope="col">Certification</th>
						<th scope="col">Supprimer</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($stagiaires as $v) { ?>
						<tr>
							<td><?= $v['id_stagiaire'] ?></td>
							<td><?= $v['nom'] ?></td>
							<td><?= $v['prenom'] ?></td>
							<td><?= $v['Jnom'] ?></td>
							<td><?= $v['Jnom'] ?></td>
							<td><?= $v['date_examen'] ?></td>
							<td><?= $v['horaire_examen'] ?></td>
							<td><?= $v['horaire_examen'] ?></td>
							<td><?= $v['horaire_examen'] ?></td>
							<td>Arriver 30min avant</td>
							<td>Apporter fiche certif</td>

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