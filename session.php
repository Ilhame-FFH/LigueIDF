<?php
include('head.php');

$req2 = 'select * from ligue_idf.session_certif s ;';

$req = 'select s.id_session, c.libelle_certification, s.libelle_session '
		. 'from ligue_idf.certification c ,ligue_idf.`session_certif` s '
		. 'where c.id_certification = s.certification_id ;';

$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();

if (isset($_POST['delete'])) {
	$req = 'DELETE FROM ligue_idf.`session_certif` WHERE id_session= "' . $_POST['id'] . '";';
	$result = $conn->prepare($req);
	try {
		$result->execute();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	header("Location: session.php");
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
					<h1 class="page-title">Liste des sessions</h1>
				</header>
				<a href="ajoutSession.php">
					<button type="button" class="btn btn-primary" name="ajoutSession">Ajouter Session</button>
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
								<td><a style="display:inline-block;width:100%;height:100%;" href="stagiaires.php?id=<?= "".$v['id_session'] ?>"><?= $v['libelle_session'] ?></a></td>
								<td><?= $v['libelle_certification'] ?></td>
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

