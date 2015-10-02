<?php
session_start();
//erase any previous search results
unset($_SESSION['SearchResults']);
unset($_SESSION['row_count']);
unset($_SESSION['SearchDetails']);
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";

try {
    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Connected successfully<hr>';
    }
catch(PDOException $e)
    {
    echo 'Connection failed: ' . $e->getMessage();
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset=UTF-8>
        <link rel="stylesheet" href="css/Style.css">
        <title>Gus Nichols Archives</title>
    </head>
    <body>
        <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <hr>
        <!--Banner and navigation bar !-->
        <div class="wrap">
        <h1>Person Search</h1>
        <form method='post' action='SearchResults.php'>
            <h3>First Name</h3>
            <input type='text' name='fname' required />
            <br><br>
            <h3>Last Name</h3>
            <input type='text' name='lname'  />
            <br><br>
            
            <h3>Yearbook</h3>
            <select name="PubName">
               <?php
               $sql = "SELECT Name FROM Publication";
               $result= $pdo->query($sql);
               echo "<option selected>All</option>";
               while($val= $result->fetch()):
               
                   $publicationName= $val['Name'];
                    {
                   echo "<option>".$publicationName."</option>";
                    }   
                endwhile;
                
                ?>
            </select>
            <br><br>
            <input type='submit' value='Search'>
                   
        </form>
        </div>
    </body>
</html>
