<?php
    session_start();
    
    include 'httpStats.php'; //Zahrnuje connect.php
    include 'logger.php';
    
    $email = $_POST['email'];
    
    //Kontrola délky e-mailu (aby nevznikaly dlouhé SQL dotazy)
    if(strlen($email) > 255)
    {
        filelog("Uživatel se pokusil zažádat o obnovu hesla, avšak neuspěl z důvodu dlouhé e-mailové adresy.");
        echo "<li>Email nesmí být delší než 255 znaků.</li>";
        die();
    }
    
    //Ochrana proti SQL injekci
    $email = mysqli_real_escape_string($connection, $email);
    
    //Kontrola platného e-mailu
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        filelog("Uživatel se pokusil zažádat o obnovu hesla, avšak neuspěl z důvodu e-mailové adresy ($email) v neplatném formátu.");
        echo "<li>E-mail nemá platný formát.</li>";
        die();
    }
    
    //E-MAIL JE OK
    
    //Kontrola existence e-mailu v databázi
    $query = "SELECT id FROM uzivatele WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo "location.href = 'errSql.html';";
        die();
    }
    if (mysqli_num_rows($result) == 0)
    {
        filelog("Uživatel se pokusil zažádat o obnovu hesla, avšak neuspěl z důvodu neznámé e-mailové adresy ($email).");
        echo "<li>K této e-mailové adrese není přidružen žádný účet.</li>";
        die();
    }
    
    //Uživatel nalezen
    $result = mysqli_fetch_array($result);
    $userId = $result['id'];
    
    //Vygenerovat kód
    $done = false;
    $code = NULL;
    do
    {
        //Vygenerovat třicetidvoumístný kód pro obnovení hesla
        $code = bin2hex(random_bytes(16));   //128 bitů --> maximálně třicetidvoumístný kód
        
        //Zkontrolovat, zda již kód v databázi neexistuje
        $query = "SELECT uzivatel_id FROM obnovenihesel WHERE kod='$code' LIMIT 1";
        $result = mysqli_query($connection, $query);
        if (!$result)
        {
            echo "location.href = 'errSql.html';";
            die();
        }
        if (!mysqli_num_rows($result) > 0)  //Kontrola případné potřeby opakování generování kódu
        {
           $done = true;
        }
    }while ($done == false);
    
    //Smazat starý kód z databáze (pokud existuje)
    $query = "DELETE FROM obnovenihesel WHERE uzivatel_id=$userId";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo "location.href = 'errSql.html';";
        die();
    }
    
    //Uložit kód do databáze
    $query = "INSERT INTO obnovenihesel (kod, uzivatel_id) VALUES ('".md5($code)."', $userId)";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo "location.href = 'errSql.html';";
        die();
    }
    
    filelog("Uživatel se zažádal o obnovu hesla prostřednictvím e-mailové adresy $email.");
    
    //Poslat e-mail.
    include 'emailSender.php';
    $emailResult = sendEmail(
        $email, 
        'Žádost o obnovu hesla na poznavacky.chytrak.cz', 
        "<span>Pro obnovení vašeho hesla klikněte na tento odkaz: </span>".
        "<a href='localhost/Poznavacky/emailPasswordRecovery.php?token=$code'>OBNOVIT HESLO</a>".
        "<br>".
        "<span>Tento odkaz bude platný po následujících 24 hodin, nebo do odeslání žádosti o nový kód.</span>".
        "<br>".
        "<span style='color: #990000; font-weight: bold;'>DŮLEŽITÉ: </span>".
        "<span style='color: #990000;'>Tento e-mail nikomu nepřeposílejte! Mohl by získat přístup k vašemu účtu.</span>"
        );
    
    if (empty($emailResult))
    {
        echo "<li style='color: #009900'>E-mail byl úspěšně odeslán</li>";
    }
    else
    {
        echo "<li>$emailResult</li>";
    }
    die();