<?php
    include 'httpStats.php';
    session_start();
    
    //Kontrola, zda je uživatel administrátorem.
    $username = $_SESSION['user']['name'];
    $query = "SELECT status FROM uzivatele WHERE jmeno='$username' LIMIT 1";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        header("Location: errSql.html");
        die();
    }
    $status = mysqli_fetch_array($result)['status'];
    if ($status !== 'admin')
    {
        //Zamítnutí přístupu
        die();
    }
    
    //Získání id poznávačky
    $pId = $_POST['oldName'];
    
    $query = "SELECT obrazekId,duvod,pocet FROM ".$pId."hlaseni ORDER BY pocet DESC LIMIT 25";
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        echo mysqli_error($connection);
    }
    
    echo "<table border=1>";
    echo "<th>Zdroj</th><th>Důvod</th><th>Počet nahlášení</th><th>Akce</th>";
        while ($report = mysqli_fetch_array($result))
        {
            echo "<tr>";
                //Získání URL obrázku
                $picId = $report['obrazekId'];
                $query = "SELECT zdroj FROM ".$pId."obrazky WHERE id=$picId";
                $innerresult = mysqli_query($connection, $query);
                $innerresult = mysqli_fetch_array($innerresult);
                $url = $innerresult['zdroj'];
                echo "<td><a href='$url'>$url</a></td>";
    
                //Výpis důvodu
                $reason = $report['duvod'];
                echo "<td>";
                switch ($reason)
                {
                    case 0:
                        echo "The picture doesn\'t load properly";
                        break;
                    case 1:
                        echo "The picture displays a different organism";
                        break;
                    case 2:
                        echo "The picture contains the name of the organism";
                        break;
                    case 3:
                        echo "The picture has bad resolution";
                        break;
                    case 4:
                        echo "The picture infringes copyright";
                        break;
                }
                echo "</td>";
                
                //Výpis počtu nahlášení
                $reporters = $report['pocet'];
                echo "<td>$reporters</td>";
                
                //Výpis akcí
                echo "<td>";
                    echo "<button class='reportAction activeBtn' onclick='showPicture(event)' title='Zobrazit obrázek'>";
                        echo "<img src='eye.gif'/>";
                    echo "</button>";
                    echo "<button class='reportAction activeBtn' onclick='disablePicture(event)' title='Skrýt obrázek'>";
                        echo "<img src='dot.gif'/>";
                    echo "</button>";
                    echo "<button class='reportAction activeBtn' onclick='deletePicture(event)' title='Odstranit obrázek'>";
                        echo "<img src='cross.gif'/>";
                    echo "</button>";
                    echo "<button class='reportAction activeBtn' onclick='deleteReport(event)' title='Odstranit hlášení'>";
                        echo "<img src='minus.gif'/>";
                    echo "</button>";
                echo "</td>";
            echo "</tr>";
        }
    echo "</table>";
    
