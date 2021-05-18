<?php
session_start();
require_once("fonctions.php");
$conn = connectionBD();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<title>Connexion</title>

		<link rel="shortcut icon" href="/Ligue_IDF-master/LigueIDF/assets/images/coq_ffh_.png">

		<link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
		<link rel="stylesheet" href="/Ligue_IDF-master/LigueIDF/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="/Ligue_IDF-master/LigueIDF/assets/css/font-awesome.min.css">

		<!-- Custom styles for our template -->
		<link rel="stylesheet" href="/Ligue_IDF-master/LigueIDF/assets/css/bootstrap-theme.css" media="screen" >
		<link rel="stylesheet" href="/Ligue_IDF-master/LigueIDF/assets/css/main.css">
	</head>
	<!-- JavaScript libs are placed at the end of the document so the pages load faster -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script src="/Ligue_IDF-master/LigueIDF/assets/js/headroom.min.js"></script>
	<script src="/Ligue_IDF-master/LigueIDF/assets/js/jQuery.headroom.min.js"></script>
	<script src="/Ligue_IDF-master/LigueIDF/assets/js/template.js"></script>
</html>