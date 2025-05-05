<?php 
$css = "category.css";
include("head.php");

if(!isset($_GET["cat_id"]) || empty($_GET["cat_id"])) {
	//pokial nie je cat_id v url hodi nas to na lost.php
	header("Location: lost.php");
}

//pokial nie je nastavena stranka, nastavi ju na 1
if (!isset($_GET["stranka"]) || empty($_GET["stranka"])) {
	$_GET["stranka"] = 1;
}

?>
<!-- Header -->
<div class="container">
<?php include("header.php")?>
<!-- Main -->
<main class="row align-items-center">
	<div>
		<div class="pages">	
			<p class="back"><a href="index.php"><i class="icofont-home"></i>Domov</a></p>
			<p><?php echo $_GET["cat_name"]; ?></p>
		</div>
		<div class="row mx-auto d-lg-flex d-none" id="cenaLokalita">
			<div class="col-8">
				<!-- odsadenie -->
			</div>
			<div class="col-2 text-center">
				<h5>Cena</h5>
			</div>
			<div class="col-2 text-center">
				<h5>Lokalita</h5>
			</div>
		</div>
		<?php 
			//stranka nie je nastavena, nastavi ju automaticky na 1
		 	if(!isset($_GET["stranka"])) {
		 		 $_GET["stranka"] = 1;
		 	}

			$cat = $_GET['cat_id'];
			$offset = ($_GET["stranka"] - 1) * 5;
			$query = "SELECT * FROM inzeraty WHERE category=$cat ORDER BY id DESC LIMIT 5 OFFSET $offset";

			displaySearch(Connect(), $query);	
		?>
		<div class="w-100 pages mt-5">
			<?php
				pages(Connect(), 5, "SELECT COUNT(*) AS total FROM inzeraty WHERE category = $cat", getUrl(), $_GET["stranka"]); 
			?>
		</div>		
	</div>
</main>
</div>
<!-- Footer -->
<?php include("footer.php")?>