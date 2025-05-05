<?php 
include ("database.php");
?><!DOCTYPE html>
<html>
<head>
	<title>Druhá ruka</title>
	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="icofont/icofont.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="icon" type="image/x-icon" href="img/ruky.ico">
	<link rel="stylesheet" type="text/css" href="mycss/basic.css">
	<link rel="stylesheet" type="text/css" href="mycss/footer.css">
	<link rel="stylesheet" type="text/css" href="mycss/header.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<?php
		if (isset($css)) {
		 	echo '<link rel="stylesheet" type="text/css" href="mycss/'.$css.'">';
		 } 
	?>
</head>
<body>
<script src="js/bootstrap.min.js"></script>
