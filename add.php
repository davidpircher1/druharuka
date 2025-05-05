<?php 
$css = "add.css";
include("head.php")
?>
<div class="container"> 
	<!-- Header -->
	<?php include("header.php")?>
	<!-- Main -->
	<main class="my-md-0 my-5 row align-items-center">
		<div>
			<?php 
				if(isset($_POST["submitAdd"])) {
					if (!required("telNumber", $_POST) || !exact_length("telNumber", 10, $_POST) || !valid_num("telNumber", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Telefonne číslo je povinné, musí mať 10 znakov bez medzier. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!required("password", $_POST) || !max_length("password", 16, $_POST) || !min_length("password", 6, $_POST)) {
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
					else if (!required("type", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Rubrika inzerátu je povinná.</div>';
					}
					else if (!required("psc", $_POST) || !exact_length("psc", 5, $_POST) || !valid_num("psc", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">PSČ je povinné, musí mať 5 znakov bez medzier. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!required("price", $_POST) || !valid_num("price", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Cena je povinná. Môže obsahovať iba číslice 0-9.</div>';
					}
					else if (!required("description", $_POST) || !max_length("description", 512, $_POST) || !min_length("description", 64, $_POST)){
						echo '<div class="alert alert-danger mt-4" role="alert">Popis musí obsahovať min 64 znakov a max 512.</div>';
					}			
					else if (!required("check", $_POST)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Pole "Prečítal som si a súhlasím so zmluvnými podmienkami" je povinné.</div>';
					}
					else if (!valid_photos($_FILES["images"], 3)) {
						echo '<div class="alert alert-danger mt-4" role="alert">Vyžadované sú 3 fotky, s veľkosťou 5MB na jednu fotku a s formátom .png, .jpeg, .jpg a .gif.</div>';
					} else {
						echo '<div class="alert alert-success mt-4" role="alert">Váš inzerát bol pridaný.</div>';
						addForm(Connect(), post("telNumber", 0, $_POST), post("password", 1, $_POST), post("username", 1, $_POST), post("itemname", 1, $_POST), post("city", 1, $_POST), post("type", 1, $_POST), post("psc", 1, $_POST), post("price", 1, $_POST), post("description", 1, $_POST), $_FILES["images"]);
					}
				}
			?>
			<form class="row g-3" method="POST" action="add.php" enctype="multipart/form-data">
			  <div class="col-md-6">
			    <label for="inputTel" class="form-label">Tel. číslo</label>
			    <input type="tel" class="form-control" id="inputTel" placeholder="0907 746 815" name="telNumber" value="<?php echo set_value("telNumber", $_POST);?>">
			  </div>
			  <div class="col-md-6">
			    <label for="inputPassword" class="form-label">Heslo</label>
			    <input type="password" class="form-control" id="inputPassword" placeholder="Heslo pre inzeráty" name="password">
			  </div>
			  <div class="col-md-6">
			    <label for="inputAddress" class="form-label">Meno</label>
			    <input type="text" class="form-control" id="inputAddress" placeholder="Vaše meno" name="username" value="<?php echo set_value("username", $_POST);?>">
			  </div>
			  <div class="col-md-6">
			    <label for="inputAddress2" class="form-label">Názov inzerátu</label>
			    <input type="text" class="form-control" id="inputAddress2" placeholder="Predám auto..." name="itemname" value="<?php echo set_value("itemname", $_POST);?>">
			  </div>
			  <div class="col-md-4">
			    <label for="inputCity" class="form-label">Mesto</label>
			    <input type="text" class="form-control" id="inputCity" placeholder="Brezno" name="city" value="<?php echo set_value("city", $_POST);?>">
			  </div>
			  <div class="col-md-3">
			    <label for="inputState" class="form-label">Rubrika</label>
			    <select id="inputState" class="form-select" name="type">
			      <option selected value="0">Vyber rubriku</option>
			      <option value="1">Auto</option>
			      <option value="2">Deti</option>
			      <option value="3">Služby</option>
			      <option value="4">Elektronika</option>
			      <option value="5">Nábytok</option>
			      <option value="6">Knihy</option>
			      <option value="7">Šport</option>
			      <option value="8">Oblečenie</option>
			      <option value="9">Záhrada</option>
			    </select>
			  </div>
			  <div class="col-md-3">
			    <label for="inputZip" class="form-label">PSC</label>
			    <input type="text" class="form-control" id="inputZip" placeholder="97701" name="psc" value="<?php echo set_value("psc", $_POST);?>">
			  </div>
			  <div class="col-md-2">
			    <label for="inputPrice" class="form-label">Cena</label>
			    <input type="text" class="form-control" id="inputPrice" placeholder="1000 &euro;" name="price" value="<?php echo set_value("price", $_POST);?>">
			  </div>
			  <div class="col-12">
			    <label for="exampleFormControlTextarea1" class="form-label">Popis inzerátu</label>
			    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" style="resize:none;" name="description"><?php echo set_value("description", $_POST);?></textarea>
			  </div>
			  <div class="col-lg-4 col-md-6 col-12">
			    <label for="formFileMultiple" class="form-label">Nahrajte fotky inzerátu (3)</label>
			    <input class="form-control" type="file" id="formFileMultiple" name="images[]" multiple>
			  </div>
			  <div class="col-md-12">
			    <div class="form-check">
			      <input class="form-check-input" type="checkbox" id="gridCheck" name="check">
			      <label class="form-check-label" for="gridCheck">
			        	Prečítal som si a súhlasím so <a href="#" style="color: var(--blue)">zmluvnými podmienkami</a>
			      </label>
			    </div>
			  </div>
			  <div class="col-md-6">
			    <button type="submit" class="btn btn-primary" name="submitAdd">Pridať</button>
			  </div>
			</form>			
		</div>
	</main>
</div>
<!-- Footer -->
<?php include("footer.php")?>