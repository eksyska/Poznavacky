<?php
    session_start();
    
    include 'httpStats.php'; //Zahrnuje connect.php
    include 'logger.php';
    
    $oldPass = urldecode($_POST['oldPass']);
    $newPass = urldecode($_POST['newPass']);
    $rePass = urldecode($_POST['reNewPass']);
    
    $userdata = $_SESSION['user'];
    $userId = $userdata['id'];
    $username = $userdata['name'];
    $savedHash = $userdata['hash'];
    
    //Kontrola délky starého hesla (aby nevznikaly dlouhé SQL dotazy)
    if (mb_strlen($oldPass) > 31)
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli dlouhému starému heslu.");
        echo "swal('Staré heslo nemohlo být delší než 31 znaků.','','error')";
        die();
    }
    
    //Kontrola délky nového hesla
    if (mb_strlen($newPass) < 6)
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli krátkému novému heslu.");
        echo "swal('Nové heslo musí být alespoň 6 znaků dlouhé.','','error')";
        die();
    }
    if (mb_strlen($newPass) > 31)
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli dlouhému novému heslu.");
        echo "swal('Nové heslo nesmí být více než 31 znaků dlouhé.','','error')";
        die();
    }
    
    /*Ochrana před SQL injekcí
    Nemusí být provedeno, protože přímé nezahešované hodnoty nejsou nikdy použity v SQL dotazu.
    $oldPass = mysqli_real_escape_string($connection, $oldPass);
    $newPass = mysqli_real_escape_string($connection, $newPass);
    $rePass = mysqli_real_escape_string($connection, $rePass);
    */
    
    //Kontrola správnosti starého hesla
    if (!password_verify($oldPass, $savedHash))
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli nesprávnému starému heslu.");
        echo "swal('Staré heslo není správné.','','error')";
        die();
    }
    
    //Kontrola znaků v hesle
    if (strlen($newPass) !== strspn($newPass, '0123456789aábcčdďeěéfghiíjklmnňoópqrřsštťuůúvwxyýzžAÁBCČDĎEĚÉFGHIÍJKLMNŇOÓPQRŘSŠTŤUŮÚVWXYZŽ {}()[]#:;^,.?!|&_`~@$%/\\+-*=\"\''))
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli přítomnosti nepovolených znaků v novém hesle.");
        echo "swal('Nové heslo obsahuje nepovolený znak.','','error')";
        die();
    }
    
    //Kontrola shodnosti hesel
    if ($newPass !== $rePass)
    {
        filelog("Uživatel $username se pokusil změnit si heslo, avšak neuspěl kvůli neshodným novým heslům.");
        echo "swal('Nová hesla se neshodují.','','error')";
        die();
    }
    
    //KONTROLA DAT V POŘÁDKU
    
    //Aktualizace hesla
    $newPass = password_hash($newPass, PASSWORD_DEFAULT);
    $query = "UPDATE uzivatele SET heslo = '$newPass' WHERE id = $userId";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo "swal('Vyskytla se chyba při práci s databází.','Pro více informací přejděte na ".$_SERVER['SERVER_NAME']."/errSql.html','error')";
        die();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'];
    filelog("Uživatel $username si změnil heslo z IP adresy $ip.");
    
    $_SESSION['user']['hash'] = $newPass;
    echo "swal('Heslo bylo úspěšně změněno.','','success');";