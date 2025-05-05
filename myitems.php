<?php 
$css = "add.css";
include("head.php");
?>
<div class="container">
<!-- Header -->
<?php include("header.php")?>
<!-- Main -->
<main class="row justify-content-center align-items-center">
	<div class="col-12">
		<?php
			if (isset($_POST["submit"])) {
				if (!required("number", $_POST) || !required("password", $_POST)) {
					echo '<div class="alert alert-danger mt-4 col-12" role="alert">Obidve polia musia byť vyplnené.</div>';
				} else {
					validLoginForm(Connect(), post("number", true, $_POST), post("password", true, $_POST));
				}
			} 
		?>
		<h2 class="text-center">Prihlásenie</h2>
		<form class="row g-3 justify-content-lg-start justify-content-center" method="POST" action="myitems.php">
			  <div class="col-lg-6 col-12">
			    <label for="inputNumber" class="form-label">Telefónne číslo</label>
			    <input type="text" class="form-control" id="inputNumber" placeholder="+421 908 123 321" name="number">
			  </div>
			  <div class="col-lg-6 col-12">
			    <label for="inputPassword4" class="form-label">Heslo</label>
			    <input type="password" class="form-control" id="inputPassword4" placeholder="Vaše heslo" name="password">
			  </div>
			  <div class="col-lg-6 col-12">
				<button type="submit" class="btn btn-primary" name="submit">Prihásiť sa</button>		  	
			  </div>
			  <div class="col-12">
 				<a href="add.php" id="add">Nemáš ešte inzerát? Pridaj ho tu!</a>						  	
			  </div>
		</form>		
	</div>
</main>
</div>
<!-- Footer -->
<?php include("footer.php")?>