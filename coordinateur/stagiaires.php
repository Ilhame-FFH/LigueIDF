<?php
include('head.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//echo $_GET['id'];
$req = 'select * from ligue_idf.session s where id_session='. $_GET['id'].';';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();
foreach ($donnees as $value) { 
	echo 'Liste des stagiaires de la session '.$value['libelle_session']; 
}
if (isset($_POST['importer'])) {
	$fichier = fopen("assets/excel/Modele_excel_stagiaires.xls", "r");
	while (!feof($fichier)) {
		$var = fgets($fichier, 4096);
	}
	echo $varr;

	fclose($fichier);
}

?>
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
					<li><a href="formation.php">Formations</a></li>
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
					<h1>Attention</h1> Télécharger le modele .xlsx et avant de l'importer Veuillez vous assurer que le fichier est en .xlsx et qu'il comporte les colonnes " statut, nom,	prenom,	date_naissance,	courriel, telephone, convoque_certification, resultats_certification, commentaires_certification.</div>
				<a href="C:\xampp\htdocs\Ligue\assets\excel\Modele_excel_stagiaires.xls" type="application/msexcel">
					<button type="button" class="btn btn-primary" name="modeleExcel">Telecharger le modele Excel</button>
				</a>
				<button type="submit" class="btn btn-primary" name="importer">Importer stagiaires</button>
				<br/>
				<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Statut</th>
								<th scope="col">Nom</th>
								<th scope="col">Prenom</th>
								<th scope="col">Date de naissance</th>
								<th scope="col">Courriel</th>
								<th scope="col">Telephone</th>
								<th scope="col">Certification</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>	
							</tr>
						</tbody>
					</table>
				</article>
			</div>
	</div>
				

