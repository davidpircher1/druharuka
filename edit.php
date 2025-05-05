<?php
$css = "edit.css";
include("head.php");

if(!isset($_SESSION["number"]) || !isset($_SESSION["valid_id"]))
	//ak nemame cislo alebo id inzeratov hodi nas to na login :)
	header("Location: myitems.php");

if (isset($_GET["id"])) {
	$id = $_GET["id"];
	$query = "SELECT * FROM inzeraty WHERE `id` = $id";
	$result = mysqli_query(Connect(), $query);
	$pole = [];
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) { 
			$pole = $row;
		}
	}
}
?>
<div class="container">
	<!-- Header -->
	<?php include("header.php")?>
	<!-- Main -->
	<main class="row align-items-center">
		<div>
			<?php 
				if(isset($_POST["submitAdd"])) { 
					if (!required("telNumber", $_POST) || !exact_length("telNumber", 10, $_POST) || !valid_num("telNumber", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Telefonne číslo je povinné, musí mať 10 znakov bez medzier. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!empty($_POST["password"]) && !max_length("passowrd", 16, $_POST) || !min_length("password", 6, $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Heslo musí mať minimálne 6 znaky a maximálne 16 znakov.</div>';
					}
					else if (!required("username", $_POST) || !max_length("username", 16, $_POST) || !min_length("username", 3, $_POST) || !valid_alpha("username", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Meno musí obsahovať min 3 znaky max 16 znakov, iba znaky a-Z.</div>';
					}
					else if (!required("itemname", $_POST) || !max_length("itemname", 32, $_POST) || !min_length("itemname", 5, $_POST)){
						echo '<div class="alert alert-danger mt-4" role="alert">Názov inzerátu je povinný a musí obsahovať min 5 znaky max 32 znakov.</div>';
					}
					else if (!required("city", $_POST) || !max_length("city", 20, $_POST) || !min_length("city", 3, $_POST) || !valid_alpha("city", $_POST)){
						echo '<div class="alert alert-danger mt-4" role="alert">Názov mesta je povinný a musí obsahovať min. 3 znaky max. 20 znakov, iba znaky a-Z.</div>';
					}
					else if (!required("psc", $_POST) || !exact_length("psc", 5, $_POST) || !valid_num("psc", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">PSČ je povinné, musí mať 5 znakov bez medzier. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!required("price", $_POST) || !valid_num("price", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Cena je povinná. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!required("description", $_POST) || !max_length("description", 512, $_POST) || !min_length("description", 64, $_POST)){
						echo '<div class="alert alert-danger mt-4" role="alert">Popis musí obsahovať min 64 znakov a max 512.</div>';
					}else {
						echo '<div class="alert alert-success mt-4" role="alert">Váš inzerát bol upravený.</div>';
						Update(Connect(),post("id",0, $_GET), post("telNumber",0, $_POST), post("password", 1, $_POST), post("username", 1, $_POST), post("itemname", 1, $_POST), post("city", 1, $_POST), post("psc", 1, $_POST), post("price", 1, $_POST), post("description", 1, $_POST));
					}
				}
			?>
			<div class="pages mb-5 mt-md-0 mt-5">	
				<p class="back"><a href="index.php"><i class="icofont-home"></i>Domov</a></p>
				<p class="back"><a href="myitemslogged.php?stranka=1">Vaše inzeráty</a></p>
				<p>Úprava inzerátu <strong><?php echo $pole["name"];?></strong></p>
			</div>
			<form class="row g-3" method="POST" action="<?php echo getUrl();?>" enctype="multipart/form-data">
			  <div class="col-md-6">
			    <label for="inputTel" class="form-label">Tel. číslo</label>
			    <input type="tel" class="form-control" id="inputTel" placeholder="0907 746 815" name="telNumber" value="<?php echo set_value("number", $pole);?>">
			  </div>
			  <div class="col-md-6">
			    <label for="inputPassword" class="form-label">Heslo nepovinné</label>
			    <input type="password" class="form-control" id="inputPassword" placeholder="Heslo pre inzeráty" name="password">
			  </div>
			  <div class="col-md-6">
			    <label for="inputAddress" class="form-label">Meno</label>
			    <input type="text" class="form-control" id="inputAddress" placeholder="Vaše meno" name="username" value="<?php echo set_value("username", $pole);?>">
			  </div>
			  <div class="col-md-6">
			    <label for="inputAddress2" class="form-label">Názov inzerátu</label>
			    <input type="text" class="form-control" id="inputAddress2" placeholder="Predám auto..." name="itemname" value="<?php echo set_value("name", $pole);?>">
			  </div>
			  <div class="col-md-4">
			    <label for="inputCity" class="form-label">Mesto</label>
			    <input type="text" class="form-control" id="inputCity" placeholder="Brezno" name="city" value="<?php echo set_value("city", $pole);?>">
			  </div>
			  <div class="col-md-4">
			    <label for="inputZip" class="form-label">PSC</label>
			    <input type="text" class="form-control" id="inputZip" placeholder="97701" name="psc" value="<?php echo set_value("psc", $pole);?>">
			  </div>
			  <div class="col-md-4">
			    <label for="inputPrice" class="form-label">Cena</label>
			    <input type="text" class="form-control" id="inputPrice" placeholder="1000 &euro;" name="price" value="<?php echo set_value("price", $pole);?>">
			  </div>
			  <div class="col-12">
			    <label for="exampleFormControlTextarea1" class="form-label">Popis inzerátu</label>
			    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" style="resize:none;" name="description"><?php echo set_value("description", $pole);?></textarea>
			  </div>
			  <div class="col-md-6">
			    <button type="submit" class="btn btn-primary" name="submitAdd">Upraviť</button>
			  </div>
			</form>			
		</div>
	</main>
</div>

<!-- Footer -->
<?php include("footer.php")?>

