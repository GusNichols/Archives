<?php
session_start();
$publicationId=11; //temporary variable!!
$_SESSION['publicationId']=$publicationId;
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Connected successfully <hr>';
    }
catch(PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="Style.css">
        <title></title>
    </head>
    <body>
        <div class="wrap">
            <?php
        foreach(glob('C:\MAMP\htdocs\GusNicholsArchives\uploads\*.jpg', GLOB_NOSORT) as $imageName)   
        {  
        //echo "Filename: " . $image . "<br />";
        
        $start  = strpos($imageName, '(');
        $end    = strpos($imageName, ')', $start + 3);
        $length = $end - $start;
        $pageNum = substr($imageName, $start + 4, $length - 4);
        //echo $pageNum . "<br />";
            try
            {
               
        $stmt = $pdo->prepare("SELECT PageId FROM Page WHERE PageNumber=? AND Publication_PublicationId=?");
        $stmt->execute(array($pageNum, $_SESSION['publicationId']));
        $results = $stmt->fetchColumn();
        if ($results)
            {
             echo $pageNum.":".$results."<br />";
             //FIX IMAGE FILE READING! UPDATES DATABASE WITH 0byte BLOB!
             $fp = fopen($imageName, r); // open a file handle of the file
               $imgContent  = fread($fp, filesize($tmpName)); // read the temp file
               fclose($fp); // close the file handle
              $stmt2 = $pdo->prepare("Update Page Set Image=? Where PageId= '".$results."' ");
              $stmt2->execute(array($imgContent));
            }
        else
            {
            echo "failed <br />";
            }
            }
        catch (Exception $e)
            {
                echo $e->getMessage();
       
            }
        }
            ?>
        </div>
    </body>
</html>
