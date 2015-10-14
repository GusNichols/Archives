<?php
session_start();
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
        <title>
    </title>
        
        <link rel="stylesheet" href="css/Style.css">
    </head>
    <body>
        <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="search.php">New Search</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
            <li><a href="SearchResults.php">Back to Page View</a></li>
        </ul>
        <hr>
        <!--Banner and navigation bar !--> 
        
        <div class='wrap'>
        <?php
        
            echo "<h1>". $_SESSION['row_count']. " Results:</h1><br>";
            if ($_SESSION['row_count'] > 0) 
             {  
                
                for($count=1;$count<=$_SESSION['row_count'];$count++)
                {   
                    echo "<img src='".$_SESSION['SearchResults'][$count]."' height='635' width='525' alt='result' >";
                    echo $_SESSION['SearchDescriptions'][$count];
                    echo $_SESSION['SearchTypes'][$count];
                }   
             } 
            else 
             {
                echo "No results found.";
             }  
        ?>
        </div>
    </body>
</html>
 
