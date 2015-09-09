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
        </ul>
        <hr>
        <!--Banner and navigation bar !--> 
        
        
        <div class='wrap'>
        <?php
        
           
            
            
            $sql= $pdo->prepare("SELECT Page_PageId FROM result WHERE Person_PersonId=?");
            $sql->execute(array($_SESSION['personId']));
            $resultPageIds=$sql->fetchAll(PDO::FETCH_ASSOC);
            $row_count = $sql->rowCount();
            echo "<h1>". $row_count. " Results:</h1><br>";
            if ($row_count > 0) 
             {
                foreach($resultPageIds as $id)
                {
                    
                    $sql2= $pdo->prepare("SELECT Image_Path FROM Page WHERE PageId=?");
                    $sql2->execute(array($id['Page_PageId']));
                    $resultPath=$sql2->fetch(PDO::FETCH_ASSOC);
                    $shortPath=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $resultPath[Image_Path]);
                    echo "<img src='".$shortPath."' height='635' width='525' alt='result' >";
                    
                }
               echo "<form action='SearchResults.php' method='POST'> <input type='hidden' name='fromViewAll' value='y'>
                            <input type='submit' value='Back to page view'>
                    </form>";    
             } 
            else {
                echo "Sorry, no results found.";
            }  

            
            
        ?>
        </div>
    </body>
</html>
 
