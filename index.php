<?php 
$css = "index.css";
include("head.php")
?>

	<div class="container"> 
		<!-- Header -->
		<?php include("header.php")?>
		<!-- Main -->
		<main class="row align-items-center my-5">
			<div class="row justify-content-around text-center">
				<!-- Load categories from database -->
				<?php displayCategories(Connect());?>
			</div>
		</main>
	</div>
	<!-- Footer -->
	<?php include("footer.php")?>
