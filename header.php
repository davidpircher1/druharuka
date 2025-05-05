<!-- Main Naviagtion -->
	<nav class="navbar navbar-expand-lg bg-transparent py-5">
	  <div class="container-fluid">
	    <a class="navbar-brand w-50" href="index.php"><img src="img/logo.svg" height="50" width="auto"></a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
	      <div class="navbar-nav">
	      	<a class="nav-link" href="index.php">Domov</a>
	        <a class="nav-link" href="myitems.php">Moje inzeráty</a>
	       	<a class="nav-link" href="add.php" >Pridať inzerát</a>
	      </div>
	    </div>
	  </div>
	</nav>

	<!-- Searching form -->
	<form class="row align-items-center searchingForm mx-auto justify-content-between" method="get" action="search.php">
	  <div class="col-lg-3 col-md-3 col-6 my-2">
	    <div class="input-group">
	      <div class="input-group-text">@</div>
	      <input type="text" class="form-control" placeholder="Hľadať" name="keyword">
	    </div>
	  </div>
	  <div class="col-lg-2 col-md-2 col-6 my-2">
	    <select class="form-select" name="category">
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
	  <div class="col-lg-2 col-md-2 col-6 my-2">
	    <div class="input-group">
	      <input type="text" class="form-control" placeholder="PSČ" name="psc">
	    </div>
	  </div>
	  <div class="col-lg-2 col-md-2 col-6 my-2">
	    <div class="input-group">
	      <input type="text" class="form-control" placeholder="&euro;" name="price">
	    </div>
	  </div>
	  <input type="text" name="stranka" value="1" style="display:none;">
	  <div class="col-lg-1 col-md-2 col-2 my-2">
	    <button type="submit" class="btn btn-primary" name="submit">Hľadať</button>
	  </div>
	</form>
	<?php 
	$resultValid = "";
	if (isset($_GET["submit"])) {
		//validuje to formular na vyhladavanie, $resultValid je vysledok
		if (!max_length("keyword", 64, $_GET) || !min_length("keyword", 3, $_GET)) {
			echo '<div class="alert alert-danger mt-4" role="alert">Kľučové slovo musí obsahovať 3-64 znakov.</div>';
		}
		else if (!exact_length("psc", 5, $_GET) || !valid_num("psc", $_GET)) {
			echo '<div class="alert alert-danger mt-4" role="alert">PSČ musí mať 5 znakov. Môže obsahovať iba číslice 0-9.</div>';
		}
		else if (!valid_num("price", $_GET)) {
			echo '<div class="alert alert-danger mt-4" role="alert">Cena môže obsahovať iba číslice 0-9.</div>';
		}
	}
	?>