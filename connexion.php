<?php
include('head.html');

if (isset($_POST['connexion'])) { // Appui sur le bouton connexion
	if (isset($_POST['login']) && isset($_POST['mdp']) ) { // Appui sur le bouton connexion
		if($_POST['login']=='administrateur@ffhandball.net')
			header('Location: administration.php');
		else{
			header('Location: session.php');
		}
	}
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
					<li><a href="index.html">Accueil</a></li>
					<li><a href="about.html">A propos</a></li>
					<li><a href="">Contact</a></li>
					<li class="active" ><a class="btn" href="connexion.php">CONNEXION</a></li>
				</ul>
			</div>
		</div>
	</div> 

	<header id="head" class="secondary"></header>

	<!-- container -->
	<div class="container">

		<div class="row">

			<!-- Article main content -->
			<article class="col-xs-12 maincontent">
				<header class="page-header">
					<h1 class="page-title"></h1>
				</header>

				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<div class="panel panel-default">
						<div class="panel-body">
							<h3 class="thin text-center">Connexion à votre compte</h3>
							<p class="text-center text-muted">Veuillez recuperer vos identifiants sur <a href="https://gesthand.net/ihand-sso/login">Gesthand</a>.</p>
							<hr>

							<form method="POST">
								<div class="top-margin">
									<label>Identifiant <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="login" >
								</div>
								<div class="top-margin">
									<label>Mot de passe <span class="text-danger">*</span></label>
									<input type="password" class="form-control" name="mdp" >
								</div>

								<hr>

								<div class="row">
									<div class="col-lg-8">
										<b><a href="">Mot de passe oublié</a></b>
									</div>
									<div class="col-lg-4 text-right">
										<button type="submit" class="btn btn-primary" name="connexion">Envoyer</button><br />
									</div>
								</div>
							</form>
						</div>
					</div>

				</div>

			</article>
			<!-- /Article -->

		</div>
	</div>	<!-- /container -->

	<?php
	include('footer.html');
	?>
</body>
</html>