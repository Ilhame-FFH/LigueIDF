<?php
require_once '../head.php';

/**
 * Gestion Administration
 * administration/administration.php
 * Accès à la liste des certifications
 * Suppresssion des certifications
 * @package     administration
 * @subpackage  Categories
 * @author      Ilhame Mouzouri <i.mouzouri@ffhandball.net>
 * @version     v.1.1 (15/05/2021)
 * @copyright   Copyright (c) 2021
 */
$req = 'SELECT * from ligue_idf.certification c ;';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();

if (isset($_POST['delete'])) {
	$fichier = readdir($rep);

	$repertoireDestination = 'upload/' . $v['id_certification'] . '/';
	$fichier = readdir($repertoireDestination);
	echo($fichier);
	unlink('upload/' . $v['id_certification'] . '/' . $fichier);
	header("Location: administration.php");
}
?>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top headroom" >
		<div class="container">
			<div class="navbar-header">
				<!-- Button for smallest screens -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="index.html"><img src="assets/images/coq_ffh_.png" width="50" height="50" alt="Ligue IDF"></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav pull-right">
					<li class="active"><a href="administration.php">Certifications</a></li>
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
					<h1 class="page-title">Liste des certifications</h1>
				</header>
				<a href="ajoutCertification.php">
					<button type="button" class="btn btn-primary" name="ajoutFormation">Ajouter Certification</button>
				</a>

				<br>
				<table class="table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Libelle Certification</th>
							<th scope="col">Famille</th>
							<th scope="col">Grille Certification</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($donnees as $v) { ?>
							<?php $repertoireDestination = 'upload/' . $v['id_certification'] . '/'; ?>
							<tr>
								<td><?= $v['id_certification'] ?></td>
								<td><?= $v['libelle_certification'] ?></td>
								<td><?= $v['famille'] ?></td>
								<?php
								if (file_exists($repertoireDestination) && is_dir($repertoireDestination) && sizeof(scandir($repertoireDestination))>2) {
									$rep = opendir($repertoireDestination);

									while ($fichier = readdir($rep)) {
										if ($fichier != '.' && $fichier != '..' && !is_dir($repertoireDestination . $fichier)) {
											?>
											<td><?= '<a href="' . $repertoireDestination . $fichier . '">' . $fichier . '</a>'; ?></td>
											<?php
										} else if ($fichier == null) {
											echo '<td>' . null . '</td>';
										}
									} closedir($rep);
								} else {
									echo '<td>' . null . '</td>';
								}
								?>
								<td> <form  method="POST">
										<!--Bouton suppression d'une rencontre-->
										<input type="submit" class="btn btn-danger" value="Supprimer" name="delete" />
										<input type="hidden" value="<?= $v['id_certification'] ?>" name="id" />
									</form></td>
							</tr>
				<?php } ?>
					</tbody>
				</table>
			</article>
		</div>
	</div>
<?php require_once '../footer.html'; ?>

</body>
</html>