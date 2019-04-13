<?php
session_start();

include 'logger.php';
$ip = $_SERVER['REMOTE_ADDR'];

$name = urldecode($_GET['name']);
$url = urldecode($_GET['url']);

if (empty($name)){die("swal('Neplatný název', '', 'error');");}
if (empty($url)){die("swal('Neplatná adresa', '', 'error');");}

include 'connect.php';
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
	die("swal('Neplatný název', '', 'error');");
}

//Kontrola, zda li daná adresa vede na obrázek
$urlCopy = $url."@";
$urlCopy = strtolower($urlCopy);

if (!(strpos($urlCopy, ".jpg@") || strpos($urlCopy, ".jpeg@") || strpos($urlCopy, ".png@") || strpos($urlCopy, ".gif@") || strpos($urlCopy, ".bmp@") || strpos($urlCopy, ".tiff@")))
{
	filelog("Uživatel ($ip) se pokusil nahrát obrázek v nesprávném formátu ($url) k přírodnině id $id v poznávačce $pName");
	die("swal('Obrázek musí být ve formátu .jpg, .jpeg, .png, .gif, .bmp nebo .tiff.', '', 'error');");
}

//Kontrola duplicitního obrázku
$url = mysqli_real_escape_string($connection, $url);
$query = "SELECT id FROM ".$table.'obrazky'." WHERE zdroj='$url'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0)
{
	filelog("Uživatel ($ip) se pokusil nahrát duplicitní obrázek k přírodnině id $id v poznávačce $pName");
	die("swal('Tento obrázek je již přidán.', '', 'error');");
}

//Vložit obrázek do databáze
$query = "INSERT INTO ".$table.'obrazky'." VALUES (NULL, $id, '$url', 1)";
$result = mysqli_query($connection, $query);
if (!$result)
{
	$err = mysqli_error($connection);
	filelog("Uživatel ($ip) nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
	die("swal('Vyskytla se neočekávaná chyba. Kontaktujte prosím správce a uveďte tuto chybu ve svém hlášení: $err);', '', 'error');");
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
	filelog("Uživatel ($ip) nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
	die("swal('Vyskytla se neočekávaná chyba. Kontaktujte prosím správce a uveďte tuto chybu ve svém hlášení: $err);', '', 'error');");
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
	filelog("Uživatel ($ip) nemohl nahrát obrázek pro přírodninu $id v poznávačce $pName, protože se vyskytla neočekávaná chyba: $err.");
	die("swal('Vyskytla se neočekávaná chyba. Kontaktujte prosím správce a uveďte tuto chybu ve svém hlášení: $err);', '', 'error');");
}
else
{
	filelog("Uživatel ($ip) nahrál nový obrázek k přírodnině id $id v poznávačce $pName");
	die("swal('Obrázek úspěšně přidán', '', 'success');");
}