<?php
session_start();
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // echo 'Connected successfully <hr>';
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
        <link rel="stylesheet" href="css/Style.css">
        <title>Select a Publication to View</title>
    </head>
    <body>
         <!--Banner and navigation bar !-->
         <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Publication</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
        <div class="wrap">
        <form action='ViewPublication.php' method='post'>
             <h1> Please select the publication that you would like to view: </h1>
             <p>    
            <select name="Name" style="height:50px; width:200px">
               <?php
               $sql = "SELECT Name FROM Publication";
               $result= $pdo->query($sql);
               while($val= $result->fetch()): // while getting all publication names from database..
               
                   $publicationName= $val['Name'];
                    {
                   echo "<option>".$publicationName."</option>"; //..it prints the names into a dropdown box
                    }   
                endwhile;
                ?>
            </select>    
             </p>
        <input type='submit' value='View Yearbook' style="height:50px; width:200px">
        </form>
        </div>
    </body>
</html>
