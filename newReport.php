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

	$url = $_GET['pic'];
	$reason = $_GET['reason'];

	//Ochrana před SQL injekcí
	$url = mysqli_real_escape_string($connection, $url);
	$reason = mysqli_real_escape_string($connection, $reason);
	
	if ($reason !== "0" && $reason != 1 && $reason != 2 && $reason != 3 && $reason != 4)
	{
		die("swal('Invalid reason!','','error');");
	}

	//Získávání id obrázku
	$table = $_SESSION['current'][0].'obrazky';

	$query = "SELECT id FROM $table WHERE zdroj='$url'";
	$result = mysqli_query($connection, $query);
	if (!$result)
	{
	    $err = mysqli_error($connection);
	    die("swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."', 'error');");
	}
	$result = mysqli_fetch_array($result);
	$picId = $result['id'];
	if(empty($picId))
	{
	    die("swal('Invalid picture','','error');");
	}
	
	
	//Zjišťování, zda je již obrázek nahlášen
	$table = $_SESSION['current'][0].'hlaseni';
	$pName = $_SESSION['current'][1];

	$query = "SELECT pocet FROM $table WHERE obrazekId=$picId AND duvod=$reason";
	$result = mysqli_query($connection, $query);
	if (gettype($result) !== "object" || mysqli_num_rows($result) <= 0)
	{
		$query = "INSERT INTO $table VALUES (NULL, $picId, $reason, 1)";	//Přidávání nového hlášení do databáze
	}
	else
	{
		//Přičítání k počtu hlášení v existujícím záznamu
		$result = mysqli_fetch_array($result);
		$newCount = ++$result['pocet'];
		$query = "UPDATE $table SET pocet = $newCount WHERE obrazekId=$picId AND duvod=$reason";
	}

	mysqli_query($connection, $query);
	filelog("Uživatel $username nahlásil obrázek s id $picId v poznávačce $pName z důvodu číslo $reason.");
	if (!mysqli_error($connection)){echo "swal('Report saved','The picture will be checked as soon as possible. Until that it will still be displayed. Please don't report it again.','success');";}
	else
	{
	    $err = mysqli_error($connection);
	    echo "swal('An unexpected error has occured. Please contact an administrator and write this error in your report:','".mysqli_real_escape_string($connection, $err)."URL: $url - Query: $query', 'error');";
	}