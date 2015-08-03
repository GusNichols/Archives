<!DOCTYPE html>
<?php
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
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="Style.css">
        <title></title>
    </head>
    <body>
        <div class="wrap">
            <?php
           foreach(glob('/uploads/*', GLOB_NOSORT) as $image)   
    {  
        echo "Filename: " . $image . "<br />";      
    }  
            ?>
        </div>
    </body>
</html>
