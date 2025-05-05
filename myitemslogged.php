<?php 
$css = "category.css";
include("head.php");
if(!isset($_SESSION["number"]) || !isset($_SESSION["valid_id"])) {
	//ak nemame cislo alebo id inzeratov hodi nas to na login :)
	header("Location: myitems.php");
}
//pokial nie je nastavena stranka, nastavi ju na 1
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
			<div id="popup">
			    <h2 class="px-5 py-2">Upozornenie!</h2>
			    <div class="px-5 py-2">
				    <p>Prajete si váš inzerát naozaj vymazať?</p>
				    <div class="row mx-auto justify-content-between">
					    <button onclick="changeDeleteValue()" class="col-5 btn btn-primary">Ano</button>
					    <button onclick="closePopup()" class="col-5 btn btn-danger">Zavrieť</button>				    	
				    </div>	    	
			    </div>
			</div>
			<div class="pages my-5">	
				<p class="back"><a href="index.php"><i class="icofont-home"></i>Domov</a></p>
				<p>Inzeráty č. <?php echo $_SESSION["number"];?></p>
			</div>
			<?php
				if (isset($_GET["delete"])) {
					//ubezpecenie, ze inzerat ma byt zmazany
					if ($_GET["delete"] === "false") {
						//ak je delete false tak zavre okno
						echo "<script>document.getElementById('popup').style.display = 'block';</script>";
					}
					if ($_GET["delete"] === "true") {
						//ak je delete true, zmazanie inzeratu
						$query = "DELETE FROM inzeraty WHERE id='{$_GET["id"]}'";
						if (mysqli_query(Connect(), $query)) {
							// Presmerujete stránku a pridáte query parameter "showMessage" s hodnotou "true"
   							echo '<script>window.location.href = window.location.href.replace(/&delete.*/, "") + "&showMessage=true";</script>';
						}
					}
				} 
				if (isset($_GET["showMessage"])) {
					if($_GET["showMessage"])
					echo '<div class="alert alert-success mt-4" role="alert">Váš inzerát bol zmazaný.</div>';
				}
			?>
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
				$offset = ($_GET["stranka"] - 1) * 5;
				// $_SESSION["valid_id"] obsahuje pole ID správnych inzerátov, prevedie ho na string oddeleny ciarkami
				$valid_id = implode(",", $_SESSION["valid_id"]);
				$query = "SELECT * FROM inzeraty WHERE `number` = '{$_SESSION["number"]}' AND `id` IN ($valid_id) ORDER BY id DESC LIMIT 5 OFFSET $offset";
				displaySearch(Connect(), $query, true);
			?>
			<div class="w-100 pages mt-5">
				<?php
					pages(Connect(), 5, "SELECT COUNT(*) AS total FROM inzeraty WHERE `number` = '{$_SESSION["number"]}' AND `id` IN ($valid_id)", getUrl(), $_GET["stranka"]); 
				?>
			</div>			
		</div>
	</main>
</div>

<script>
    function closePopup() {
        document.getElementById("popup").style.display = "none"
		var currentUrl = window.location.href;
		var newUrl = currentUrl;
		
		if (currentUrl.includes("&showMessage=true")) {
			// URL už obsahuje GET parametre
			newUrl = currentUrl.replace(/&showMessage.*/, '');
		} else if (currentUrl.includes("&delete=false")) {
		    // URL už obsahuje GET parametre
		    newUrl = currentUrl.replace(/&delete.*/, '');
		} 

		// Presmerovanie na novú URL
		window.location.href = newUrl;
    }
    function changeDeleteValue() {
        //ziska aktualnu url
        var currentUrl = window.location.href;

        // Zmena alebo pridanie GET parametru "delete"
        var newUrl;
        if (currentUrl.indexOf('?') !== -1) {
            // URL uz obsahuje GET parametre
            newUrl = currentUrl.replace(/(\?|\&)delete=.*?(&|$)/, '$1delete=true$2');
        }
        // Presmerovanie na novou URL
        window.location.href = newUrl;
    }
</script>
<!-- Footer -->
<?php include("footer.php")?>