
<?php
require 'vendor\autoload.php';

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

// Connect to database
include('head.php');

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
	//echo $file;
	$session_id = $conn->query('select id_session from ligue_idf.session_certif s where id_session=' . 4 . ';')->fetch()['id_session'];
	echo $session_id;
	if (file_exists($file)) {
		$tab = file($file);
		$donnees = lire_csv("Feuil2.csv");
		$size = count($donnees);
		$tab;

		for ($i = 1; $i < $size; $i++) {
			//print_r($donnees[$i]) . "<br />\n";
			foreach ($donnees[$i] as $donnee) {
				
				//echo $donnee;
				$tab = explode(',', $donnee);
				print_r($tab);
				//print_r($tab[0]);
				/*$message = strip_tags($tab[0]);
				$message = preg_replace("/[^a-zA-Z0-9\s]/", "", $tab[0]);
				print_r($tab[0]);*/

				//echo 'FINI';
				$query = "INSERT INTO stagiaire (formation, session, statut, nom, prenom, date_naissance, courriel, telephone, comite, club, convoque_certification, date_debut, date_fin) "
						. "VALUES (:formation, :session, :statut, :nom, :prenom, :date_naissance, :courriel, :telephone, :comite, :club, :convoque_certification, :date_debut, :date_fin)";
				$req = $conn->prepare($query);
				/*$req->execute(array(
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
					'date_fin' => $tab[12]

					));*/
			}
		}
	}
}
?>

<html>
	<body>
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
	</body>
</html>
