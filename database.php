<?php 
//zapol som session, ak ju bude treba, bude vsade
session_start();

//pripojenie do databazy
function Connect() {
	$conn = mysqli_connect("localhost", "druharukaadmin","Dedulko007", "druharuka1234");
	//nastavi znakovu sadu databazy na utf8
	mysqli_set_charset($conn,"utf8");
	return $conn;
}

//ziskat aktualnu url adresu
function getUrl() {
	$currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	return $currentURL;
}

//zobrazenie kategorii na index.php
function displayCategories($conn) {
	$query = "SELECT * FROM categories";
	$result = mysqli_query($conn, $query);

	while($row = mysqli_fetch_assoc($result)) {
		echo "<div class='col-lg-3 col-md-3 col-sm-4 col-4 category my-4'>
				<div>
					<h4>".$row["cat_name"]."</h4>
					<p>".$row["cat_desc"]."</p>
				</div>
				<a href='category.php?cat_id=".post("cat_id", true, $row)."&cat_name=".post("cat_name", true, $row)."&stranka=1'><img src='img/".$row["cat_img"]."' height='120' width='120'></a>
			</div>";
	}
}


//vyhladava itemy v databaze
function searchItems($conn, $keyword, $category, $psc, $price, $page, $usage) {
	//odstranim medzery pred pridanim do query odstranim medzery pred pridanim do query a osetrim vstup proti utoku
	$psc = str_replace(' ', '', post("psc", 1, $_GET));
	//odstranim medzery pred pridanim do query a osetrim vstup proti utoku
	$price = str_replace(' ', '', post("price", 1, $_GET));
	//pred pridanim do query a osetrim vstup proti utoku
	$keyword = post("keyword", 1, $_GET);
	//offset urcuje kolko riadkov ma preskocit :), ak je stranka na prvej stranke teda (1-1) * 5 = 0
	$offset = ($page - 1) * 5;

	if($usage == "select") {
		//vyberam realne z databazy
		$query = "SELECT * FROM inzeraty WHERE 1"; // Začneme "WHERE 1", co je vzdy pravda		
	} else if($usage == "total") {
		//chcem len pocet, kvoli strankovaniu
		$query = "SELECT COUNT(*) AS total FROM inzeraty WHERE 1";
	}
	//pokial nie su prazdne, prida to do vyhladavania
	if (!empty($keyword))
	    $query .= " AND LOWER(name) LIKE LOWER('%$keyword%')";
	if (!empty($category))
	    $query .= " AND category = $category";
	if (!empty($psc))
	    $query .= " AND psc = $psc";
	if (!empty($price))
	    $query .= " AND price <= $price";
	// zoradi to od posledneho //limit do sql na strankovanie
	if($usage == "select")
		$query .= " ORDER BY id DESC LIMIT 5 OFFSET $offset"; 		

	return $query;
}

//zobrazi vysledky hladania searchItems()
function displaySearch($conn, $query, $edit=false, $item_url=0) {
	try {
	    $result = mysqli_query($conn, $query);

	    if (!$result) {
	        throw new Exception("Database Query Error");
	    }
	} catch (Exception $e) {
	    echo '<div class="text-center mt-4"><h1 class="mb-5">Nenašli sme nič, čo ste hľadali. Skúste to znovu.</h1><a href="index.php"><img src="img/lost.svg" height="250" width="auto"></a></div>';
	    echo '<script>document.getElementById("cenaLokalita").classList.remove("d-lg-flex")</script>';
	    echo '<script>document.getElementsByClassName("pages")[0].style.display = "none";</script>';
	    return 0; // Ukončenie funkcie
	}
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) { 
			if (isset($_GET["cat_id"]) && isset($_GET["cat_name"])) {
				//inzerat je hladany cez kategorie
				$item_url = "item.php?item_id=".post("id", true, $row)."&cat_id=".post("cat_id", true, $_GET)."&cat_name=".post("cat_name", true, $_GET);
			} else {
				//inzerat nie je hladany skrz kategorie
				$item_url = "item.php?item_id=".post("id", true, $row);
			}
			echo "<div class='row mx-auto item py-3'>
				<div class='col-lg-4 col-md-6 col-12 order-lg-first order-md-first mb-lg-0 mb-md-2 mb-2'>
					<a href='".$item_url."'><div style='background-image: url(items_img/".$row["img"].");'class='items_img'></div></a>
				</div>
				<div class='col-lg-4 col-md-6 col-12 order-first'>
					<h4><a href='item.php?item_id=".post("id", true, $row)."'>".$row["name"]."</a></h4>
					<p class='my-lg-0 my-3 description'>".substr($row["description"], 0, 250)."...</p>
				</div>
				<div class='col-lg-2 col-md-2 col-3 text-lg-center text-md-start edit-hide'>
					<p>".$row["city"]."</p>
					<p>".$row["psc"]."</p>
				</div>
				<div class='col-lg-2 col-md-2 col-3 text-lg-center text-md-start edit-hide'>
					<p><strong>".$row["price"]."&euro;</strong></p>
				</div>
				<div class='col-12 text-end edit'>
					<a href='edit.php?id=".post("id", true, $row)."' class='me-2'>Upraviť</a>
					<a href='".getUrl()."&delete=false&id=".post("id", true, $row)."'>Zmazať</a>
				</div>
			</div>";
		}
		if (!$edit) {
		    // Pokial je $edit false, prida display: none pre všetky prvky s triedou "edit".
		    echo '<script>
		        window.onload = function() {
		            var elements = document.getElementsByClassName("edit");
		            for (var i = 0; i < elements.length; i++) {
		                elements[i].style.display = "none";
		            }
		        };
		    </script>';
		} else {
			echo '<script>
		        window.onload = function() {
		            var elements = document.getElementsByClassName("edit-hide");
		            for (var i = 0; i < elements.length; i++) {
		                elements[i].style.display = "none";
		            }
		        };
		    </script>';
		}
	} else {
		//vypise chybovu hlasku
		echo '<div class="text-center mt-4"><h1 class="mb-5">Nenašli sme nič, čo ste hľadali. Skúste to znovu.</h1><a href="index.php"><img src="img/lost.svg" height="250" width="auto"></a></div>';
		//odstrani mi z panelu d-lg-flex, takze mi to nebude zavadzat, ked nebude vysledok ziadny
		echo '<script>document.getElementById("cenaLokalita").remove("d-lg-flex")</script>';
		//schova to navigaciu domov/kategoria...
		echo '<script>document.getElementsByClassName("pages")[0].style.display = "none";</script>';
	}
}

//zobrazi konkretnu polozku uz na velkom (item.php)
function  displayItem($conn, $item_id) {
	$query = "SELECT * FROM inzeraty WHERE id = $item_id";
	$result = mysqli_query(Connect(), $query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) { 
			echo "<div class='row'>
					<div class='col-lg-6 col-12'><div id='carouselExampleFade' class='carousel slide carousel-fade'>
						  <div class='carousel-inner'>
						    <div class='carousel-item active'>
						      <img src='items_img/".$row["img"]."' class='w-100' alt='image1'>
						    </div>
						    <div class='carousel-item'>
						      <img src='items_img/".$row["img2"]."' class='w-100' alt='image2'>
						    </div>
						    <div class='carousel-item'>
						      <img src='items_img/".$row["img3"]."' class='w-100' alt='image3'>
						    </div>
						  </div>
						  <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleFade' data-bs-slide='prev'>
						    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
						    <span class='visually-hidden'>Previous</span>
						  </button>
						  <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleFade' data-bs-slide='next'>
						    <span class='carousel-control-next-icon' aria-hidden='true'></span>
						    <span class='visually-hidden'>Next</span>
						  </button>
						</div>
					</div>
					<div class='col-lg-6 col-md-12 mt-lg-0 mt-4 d-lg-flex align-items-start flex-column d-block'>
						<div class='mb-auto'>
							<h2>".$row["name"]." za ".$row["price"]."&euro;</h2>
							<div class='description mb-5'>".$row["description"]."</div>
						</div>
					</div>
				</div>
				<div class='information mt-4'>
					<p><strong>Meno:</strong> ".$row["username"]."</p>
					<p><strong>Lokalita:</strong> ".$row["city"]."</p>
					<a href='tel:".$row["number"]."' class='mt-3'><button class='btn btn-primary p-2'>Kontaktovať</button></a>
				</div>";
		}
	}
}

//cislujem stranky :P
function pages($conn, $itemsNumber, $query_count, $url, $stranka) {
	try {
    	$result_count = mysqli_query($conn, $query_count);
    	// chyba pokracujeme chyb. hlaskou
    	if (!$result_count) {
	        throw new Exception("Database Query Error");
	    }
	} catch (Exception $a) {
		//ak je chyba v dotaze schova to strankovanie
		echo '<script>document.getElementsByClassName("pages")[1].style.display = "none";</script>';
		//return mi ukonci funkciu, kedze je tu chyba
		return 0;
	}
	$row_count = mysqli_fetch_assoc($result_count);
	$totalPages = ceil($row_count['total'] / $itemsNumber);
	if ($totalPages == 0) {
		//ak su stranky rovne 0, to znamena, ze neexistuje ziadny inzerat
		echo '<script>document.getElementsByClassName("pages")[1].style.display = "none";</script>';
	}

	for ($i = $stranka - 1; $i <= $stranka+1; $i++) {
		//osetrenie prvych 3 stranok, aby mi zobrazilo 1,2,3
		if($i == 0) {
			for ($i=1; $i < 4; $i++) { 
				//osetrenie aby som nedostal nerealnu stranku na last page, pokial existuje menej ako 3 stranky
				if($i > $totalPages) {
					break;
				}
				//odstrani premennu stranka z url
				$url = preg_replace('/[?&]stranka=\d+/', '', $url);
				//pokial stranka neobsahuje ?, pridame ho tam, tak keby sa parametre zacinaju & hodi mi to chybu
				if (strpos($url, '?') === false) {
				    $url .= "?";
				}
				if ($i == 1) {
					//prva stranka je aktualna, tak bude zvyraznena
					echo "<a href='".$url."&stranka=$i' style='font-weight: bold;'>".$i."</a> ";
				} else {
					echo "<a href='".$url."&stranka=$i'>".$i."</a> ";
				}
			}
			break;
		}
		//osetrenie aby som nedostal nerealnu stranku na last page 
		else if($i > $totalPages) {
			break;
		}
		//odstrani premennu stranka z url
		$url = preg_replace('/[?&]stranka=\d+/', '', $url);
		//pokial stranka neobsahuje ?, pridame ho tam, tak keby sa parametre zacinaju & hodi mi to chybu
		if (strpos($url, '?') === false) {
		    $url .= "?";
		}
		//vypise stranky
		if ($i == $stranka) {
			//aktualna stranka je zvyraznena
			echo "<a href='".$url."&stranka=".$i."' style='font-weight: bold;'>".$i."</a> ";
		} else {
			echo "<a href='".$url."&stranka=".$i."'>".$i."</a> ";
		}
	}
}

//osetri mi to vstupy pred utokmi
function post($field, $protection, $type){
	$str = NULL;
	if( isset($type[$field]) ){
		$str = trim($type[$field]);
		if( $protection ){
			// najskor skonvertujeme retazec na UTF znakovu sadu aby sme odstranili znaky z inych sad, ktore sa konvertuju ako spec. znaky a mozu narobit problemy
			// https://www.php.net/manual/en/function.mb-convert-encoding
			$str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
			// odstranime HTML tagy
			// https://www.php.net/manual/en/function.strip-tags
			$str = strip_tags($str);
			// skonvertujem specialne znaky na HTML entity
			// https://www.php.net/manual/en/function.htmlspecialchars.php
			$str = htmlspecialchars($str);
			// odstrani \r \n \t ...
			// https://www.php.net/manual/en/function.stripcslashes
			$str = stripcslashes($str);
			// konvertje '' a "" na HTML entity
			// https://www.php.net/manual/en/function.htmlentities
			$str = htmlentities($str, ENT_QUOTES, 'UTF-8');
			//mysql injection osetrenie
			$str = mysqli_real_escape_string(Connect(), $str);
		}
	}
	return $str;
}

//nastavi mi hodnoty vo formulari
function set_value($field, $type, $default=''){
	$str = post($field, false, $type);
	// pouzivame === aby sme najskor porovnali datovy typ
	// pri pouziti == by sa NULL == '' == false
	if( $str === NULL ){
		// neexistuje, formular sa nacital prvy krat
		return $default;
	} else {
		// formular bol odoslany, pouzije sa hodnota s POSTu
		return $str; // post($field, false)
	}
}

function required($field, $type){
	$str = trim($type[$field]);
	if( !empty($str) ){
		return TRUE;
	}
	return FALSE;
}


function min_length($field, $length, $type){
	$str = trim($type[$field]);
	if( empty($str) )
		return TRUE;
	if( mb_strlen($str) < $length)
		return FALSE;
	return TRUE;
}

function max_length($field, $length, $type){
	$str = trim($type[$field]);
	if( !empty($str) && mb_strlen($str) > $length )
		return FALSE;
	return TRUE;
}


function exact_length($field, $length, $type){
	$str = trim($type[$field]);
	$str = str_replace(' ', '', $str);
	if( !empty($str) && mb_strlen($str) != $length )
		return FALSE;
	return TRUE;
}

//funkcia, ktora nam odstrani interpunkcne znamienka a diakritiku
function odstranDiakritiku($text) {
    $diakritika = array(
        'á' => 'a', 'ä' => 'a', 'â' => 'a', 'à' => 'a', 'ã' => 'a', 'å' => 'a',
        'č' => 'c', 'ç' => 'c',
        'ď' => 'd',
        'é' => 'e', 'ě' => 'e', 'ë' => 'e', 'è' => 'e', 'ê' => 'e',
        'í' => 'i', 'ï' => 'i', 'ì' => 'i', 'î' => 'i',
        'ľ' => 'l', 'ĺ' => 'l', 'ň' => 'n',
        'ó' => 'o', 'ö' => 'o', 'ô' => 'o', 'ò' => 'o', 'õ' => 'o', 'ø' => 'o',
        'ř' => 'r',
        'š' => 's', 'ß' => 's',
        'ť' => 't',
        'ú' => 'u', 'ů' => 'u', 'ü' => 'u', 'ù' => 'u', 'û' => 'u',
        'ý' => 'y',
        'ž' => 'z',
        'Á' => 'A', 'Ä' => 'A', 'Â' => 'A', 'À' => 'A', 'Ã' => 'A', 'Å' => 'A',
        'Č' => 'C', 'Ç' => 'C',
        'Ď' => 'D',
        'É' => 'E', 'Ě' => 'E', 'Ë' => 'E', 'È' => 'E', 'Ê' => 'E',
        'Í' => 'I', 'Ï' => 'I', 'Ì' => 'I', 'Î' => 'I',
        'Ľ' => 'L', 'Ĺ' => 'L', 'Ň' => 'N',
        'Ó' => 'O', 'Ö' => 'O', 'Ô' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ø' => 'O',
        'Ř' => 'R',
        'Š' => 'S',
        'Ť' => 'T',
        'Ú' => 'U', 'Ů' => 'U', 'Ü' => 'U', 'Ù' => 'U', 'Û' => 'U',
        'Ý' => 'Y',
        'Ž' => 'Z'
    );

    return strtr($text, $diakritika);
}

function valid_alpha($field, $type){
	$str = trim($type[$field]);
	$str = str_replace(' ', '', $str);
	if( !empty($str) && !ctype_alpha(odstranDiakritiku($str)) ){
		return FALSE;
	}
	return TRUE;
}

function valid_num($field, $type){
	$str = trim($type[$field]);
	$str = str_replace(' ', '', $str);
	if( !empty($str) && !is_numeric($str) )
		return FALSE;
	return TRUE;
}

function valid_photos($field, $number) {
	if (!empty($field["name"]) && count($field["name"]) == $number) {
		for ($i=0; $i < 3; $i++) { 
			//funkcia pathinfo nam zisti priponu suboru
			$format = pathinfo($field["name"][$i], PATHINFO_EXTENSION);
			//ak sa subor nerovna ziadnej z koncoviek tak nam da error a ukonci cyklus predcasne
			if ($format != "png" && $format != "jpg" && $format != "jpeg" && $format != "gif" && $format != "jpe" && $format != "PNG") {
				echo '<div class="alert alert-danger mt-4" role="alert">Povolené formáty fotiek sú .png, .jpeg, .jpg a .gif.</div>';
				return false;
			}
		}
		for ($i=0; $i < 3; $i++) { 
			$maxFileSize = 5; // Maximálna veľkosť 5 MB
			for ($i = 0; $i < 3; $i++) {
			    $fileSize = $field['size'][$i] / 1000000; //kedze je to v kb tak to musim delit na mb
			    if ($fileSize > $maxFileSize) {
					echo '<div class="alert alert-danger mt-4" role="alert">Maximálna povolená veľkost je 5 MB.</div>';
					return false;
			    }
			}
		}
	} else {
		return false;
	}	
	return true;
}

//pridame do databazy inzerat
function addForm($conn, $number, $password, $username, $itemName, $city, $type, $psc, $price, $description, $images) {
		//odstranim medzery z cisla pred pridanim do databazy
		$number = str_replace(' ', '', $number);
		//zahashujem heslo
		$password = password_hash($password, PASSWORD_DEFAULT);

		//sql kod do databazy.
		$query = "INSERT INTO inzeraty (`name`, `description`, `username`, `price`, `psc`, `city`, `number`, `category`, `img`, `img2`, `img3`, `password`) VALUES ('$itemName', '$description', '$username', '$price', '$psc', '$city', '$number', '$type', '{$images['name'][0]}', '{$images['name'][1]}', '{$images['name'][2]}', '$password')";
		mysqli_query($conn, $query);

		//pridavam fotky do priecinku
		for ($i=0; $i < 3; $i++) { 
			$target_file = "items_img/" . $images["name"][$i];
			move_uploaded_file($images["tmp_name"][$i], $target_file);
		}
}

function validLoginForm($conn, $number, $password) {
	$number = str_replace(' ', '', $number);
	//inicializujem ako pole pre dobre inzeraty
	$_SESSION["valid_id"] = array();

	$query = "SELECT `number`, `password`, `id` FROM inzeraty WHERE `number` = '$number'";
	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result) > 0) {
	    // Existuje záznam s daným číslom
	    while($row = mysqli_fetch_assoc($result)) {
	   		// Overenie hesla pomocou password_verify()
		    if (password_verify($password, $row["password"])) {
		        // Heslo je správne, nastavenie session premennych a presmerovanie
		        $_SESSION["number"] = $row["number"];
		        //ukladam id, ktore maju rovnake cislo a heslo
		        $_SESSION["valid_id"][] = $row["id"];
		        header("Location: myitemslogged.php?stranka=1");
		    } 
	    }

	     // Heslo neni správne
	    echo '<div class="alert alert-danger mt-4" role="alert">Neprihlásený. Nesprávne heslo.</div>';
	} else {
	    // Záznam s daným číslom neexistuje
	    echo '<div class="alert alert-danger mt-4" role="alert">Neprihlásený. Neplatné číslo.</div>';
	}
}

//updatneme inzerat
function Update($conn, $id, $number, $password, $username, $itemName, $city, $psc, $price, $description) {
		//odstranim medzery z cisla pred pridanim do databazy
		$number = str_replace(' ', '', $number);
		//zahashujem heslo
		if (!empty($password) && isset($password)) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$_SESSION["password"] = $password;
			//sql kod do databazy.
			$query = "UPDATE inzeraty SET `name`='$itemName', `description`='$description', `username`='$username', `price`='$price', `psc`='$psc', `city`='$city', `number`='$number', `password`='$password' WHERE id=$id";
		} else {
			//sql kod do databazy.
			$query = "UPDATE inzeraty SET `name`='$itemName', `description`='$description', `username`='$username', `price`='$price', `psc`='$psc', `city`='$city', `number`='$number' WHERE id=$id";
		}
		mysqli_query($conn, $query);
}

?>


