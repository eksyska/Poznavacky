<?php
	session_start();

	include 'httpStats.php'; //Zahrnuje connect.php
	include 'logger.php';

	if (!isset($_SESSION['current']))	//Poznávačka nenastavena --> přesměrování na stránku s výběrem
	{
		echo "location.href = 'list.php';";
		die();
	}

	$username = $_SESSION['user']['name'];

	$name = urldecode($_GET['name']);
	$url = urldecode($_GET['url']);

	if (empty($name)){die("swal('Invalid name', '', 'error');");}
	if (empty($url)){die("swal('Invalid address', '', 'error');");}

	$table = $_SESSION['current'][0];
	$pName = $_SESSION['current'][1];

	//Získat název přírodniny.
	$final = "";
	$arr = str_split($name);
	for ($i = count($arr) - 1; $arr[$i] != '('; $i--){}
	for ($j = 0; $j < $i - 1; $j++){$final .= $arr[$j];}
	$final = mysqli_real_escape_string($connection, $final);

	//Získat ID přírodniny
	$query = "SELECT id FROM ".$table.'seznam'." WHERE nazev='$final'";
	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) > 0)
	{
		$id = mysqli_fetch_array($result);
		$id = $id['id'];
	}
	else
	{
		die("swal('Invalid name', '', 'error');");
	}

	//Kontrola, zda li daná adresa vede na obrázek
	$urlCopy = $url."@";
	$urlCopy = strtolower($urlCopy);

	if (!(strpos($urlCopy, ".jpg@") || strpos($urlCopy, ".jpeg@") || strpos($urlCopy, ".png@") || strpos($urlCopy, ".gif@") || strpos($urlCopy, ".bmp@") || strpos($urlCopy, ".tiff@")))
	{
		filelog("Uživatel $username se pokusil nahrát obrázek v nesprávném formátu ($url) k přírodnině id $id v poznávačce $pName");
		die("swal('The picture has to be in .jpg, .jpeg, .png, .gif, .bmp or .tiff. format', '', 'error');");
	}

	//Kontrola duplicitního obrázku
	$url = mysqli_real_escape_string($connection, $url);
	$query = "SELECT id FROM ".$table.'obrazky'." WHERE zdroj='$url'";
	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) > 0)
	{
		filelog("Uživatel $username se pokusil nahrát duplicitní obrázek k přírodnině id $id v poznávačce $pName");
		die("swal('This picture has already been added', '', 'error');");
	}

	//Vložit obrázek do databáze
	$query = "INSERT INTO ".$table.'obrazky'." VALUES (NULL, $id, '$url', 1)";
	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		$err = mysqli_error($connection);
		filelog("Uživatel $username nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
		die("swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."', 'error');");
	}
    
	//Zvýšit autorovy obrázku počet nahraných obrázků v databázi
	$_SESSION['user']['addedPics'] = ++$_SESSION['user']['addedPics'];
	$newAmount = $_SESSION['user']['addedPics'];
	$query = "UPDATE uzivatele SET pridaneObrazky = $newAmount WHERE jmeno = '$username'";
	$result = mysqli_query($connection, $query);
	if (!$result)
	{
	    $err = mysqli_error($connection);
	    filelog("Uživatel $username nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
	    die("swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."', 'error');");
	}
	
	//Upravit počet obrázků dané přírodniny v tabulce seznam
	$query = "SELECT obrazky FROM ".$table.'seznam'." WHERE id = $id";
	$result = mysqli_query($connection, $query);
	$result = mysqli_fetch_array($result);
	$newAmount = $result['obrazky'];
	$newAmount++;
	$query = "UPDATE ".$table.'seznam'." SET obrazky = $newAmount WHERE id = $id";
	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		$err = mysqli_error($connection);
		filelog("Uživatel $username nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
		die("swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."', 'error');");
	}

	//Upravit počet obrázků dané přírodniny v tabulce poznavacky
	$query = "SELECT obrazky FROM poznavacky WHERE id = $table";
	$result = mysqli_query($connection, $query);
	$result = mysqli_fetch_array($result);
	$newAmount = $result['obrazky'];
	$newAmount++;
	$query = "UPDATE poznavacky SET obrazky = $newAmount WHERE id = $table";
	$result = mysqli_query($connection, $query);
	if (!$result)
	{
		$err = mysqli_error($connection);
		filelog("Uživatel $username nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
		die("swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."', 'error');");
	}
	else
	{
		filelog("Uživatel $username nahrál nový obrázek k přírodnině id $id v poznávačce $pName");
		die("swal('The picture has been added successfuly', '', 'success');");
	}