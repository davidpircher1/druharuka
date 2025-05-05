<?php
$css = "item.css";
include("head.php");

//pokial nie je item_id v url hodi nas to na lost.php
if(!isset($_GET["item_id"]) || empty($_GET["item_id"])) {
	header("Location: lost.php");
}
//pokial nie je nastavena stranka, nastavi ju na 1
if (!isset($_GET["stranka"]) || empty($_GET["stranka"])) {
	$_GET["stranka"] = 1;
}
$item_id = $_GET["item_id"];

$query = "SELECT name FROM inzeraty WHERE id = $item_id";
$result = mysqli_query(Connect(), $query);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) { 
		$name = $row["name"];
	}
}


?>
<div class="container">
	<!-- Header -->
	<?php include("header.php")?>
	<!-- Main -->
	<main class="row align-items-center">
		<div>
			<div class="pages my-5">	
				<p class="back"><a href="index.php"><i class="icofont-home"></i>Domov</a></p>
				<p class="back">
					<?php 
						if(isset($_GET["cat_id"]) && isset($_GET["cat_name"])) {
							echo '<a href="category.php?cat_id='.$_GET["cat_id"].'&cat_name='.$_GET["cat_name"].'&stranka='.$_GET["stranka"].'">'.$_GET["cat_name"].'</a>';
						} else {
							echo 'Váš hľadaný inzerát';
						}
					?>
				</p>
				<p><?php echo $name;?></p>
			</div>
			<?php displayItem(Connect(), $_GET["item_id"]);?>			
		</div>
	</main>
</div>

<!-- Footer -->
<?php include("footer.php")?>

