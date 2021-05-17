<?php
	include('head.php');
	$req = 'select s.id_session, f.libelle_formation, s.libelle_session '
			. 'from ligue_idf.formation f ,ligue_idf.`session` s '
			. 'where f.id_formation = s.formation_id ;';
	$result = $conn->prepare($req);
	try {
		$result->execute();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	$donnees = $result->fetchAll();

	if (isset($_POST['delete'])) {
		$req = 'DELETE FROM ligue_idf.`session` WHERE id_session= "' . $_POST['id'] . '";';
		$result = $conn->prepare($req);
		try {
			$result->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
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
						<li><a href="saison.php">Saisons</a></li>
						<li><a href="session.php">Sessions de certification</a></li>
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
						<h1 class="page-title">Liste des sessions</h1>
					</header>
					<a href="ajoutFormation.php">
						<button type="button" class="btn btn-primary" name="ajoutFormation">Ajouter Formation</button>
					</a>
					
					<a href="ajoutSession.php">
						<button type="button" class="btn btn-success" name="ajoutSession">Ajouter session</button>
					</a>
					<br>
					<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Session</th>
								<th scope="col">Formation associé</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($donnees as $v) { ?>
							<tr>
								<td><?= $v['id_session'] ?></td>
								<td><?= $v['libelle_session'] ?></td>
								<td><?= $v['libelle_formation'] ?></td>
								<td> <form action="coordinateur.php" method="POST">
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

		<!-- JavaScript libs are placed at the end of the document so the pages load faster -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="assets/js/headroom.min.js"></script>
		<script src="assets/js/jQuery.headroom.min.js"></script>
		<script src="assets/js/template.js"></script>
	</body>
</html>
