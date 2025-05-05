<?php
	$css = "category.css";
	include("head.php");
	if (!isset($_GET["stranka"]) || empty($_GET["stranka"])) {
	$_GET["stranka"] = 1;
	}
?>
<div class="container">
	<!-- Header -->
	<?php include("header.php")?>
	<!-- Main -->
	<main class="row align-items-center">
		<div>
			<div class="pages">	
				<p class="back"><a href="index.php"><i class="icofont-home"></i>Domov</a></p>
				<p>Vyhľadávanie</p>
			</div>
			<div class="row mx-auto d-lg-flex d-none" id="cenaLokalita">
				<div class="col-8">
					<!-- odsadenie -->
				</div>
				<div class="col-2 text-center">
					<h5>Lokalita</h5>
				</div>
				<div class="col-2 text-center">
					<h5>Cena</h5>
				</div>
			</div>	
			<?php
				displaySearch(Connect(), searchItems(Connect(), $_GET["keyword"], $_GET["category"], $_GET["psc"], $_GET["price"],$_GET["stranka"], "select"));	
			?>	
			<div class="w-100 pages">
				<?php
				pages(Connect(), 5, searchItems(Connect(), $_GET["keyword"], $_GET["category"], $_GET["psc"], $_GET["price"],$_GET["stranka"],"total"), getUrl(), $_GET["stranka"]); 
				?>
			</div>	
		</div>
	</main>
</div>
<!-- Footer -->
<?php include("footer.php")?>