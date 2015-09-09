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
            <li><a href="search.php">Search</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <hr>
        <!--Banner and navigation bar !--> 
        
        
        <div class='wrap'>
        <?php
        //DOES NOT WORK!! ----------------------------------------
            $personId= findName($_POST['lname'],$_POST['fname'],$pdo);
            //echo "<p> The person's id in the database is: </p>".$personId."<br>";
            echo "<p> Results:</p><br>";
            $sql= $pdo->prepare("SELECT Page_PageId FROM result WHERE Person_PersonId=?");
            $sql->execute(array($personId));
            $resultPageIds=$sql->fetchAll(PDO::FETCH_ASSOC);
            $row_count = $sql->rowCount();
            if ($row_count > 0) 
             {
                foreach($resultPageIds as $id)
                {
                    //echo $id[Page_PageId]."<br>";
                    $sql2= $pdo->prepare("SELECT Image_Path FROM Page WHERE PageId=?");
                    $sql2->execute(array($id['Page_PageId']));
                    $resultPath=$sql2->fetch(PDO::FETCH_ASSOC);
                    $shortPath=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $resultPath[Image_Path]);
                    echo "<img src='".$shortPath."' height='635' width='525' alt='result' >";
                    //echo "Path is:".$resultPath[Image_Path]."<br>";
                }
                   
             } 
            else {
                echo "Your search returned 0 results.";
            }  

            
            echo "<p>".$row_count."results</p>";
        ?>
        </div>
    </body>
</html>
 
<?php
 function findName($last, $first, $pdo)
    {
        //manual return since it can't find personID 1 for "unknown" entries
       if($last === "Unknown")
        {
          return 1;
        }
       
        $stmt = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND FirstName=?");
        $stmt->execute(array($last, $first));
        $results = $stmt->fetchColumn();
        if (!$results)
        {
          return 2; // number for exceptions that couldn't import properly
        }
        else
        {
         return $results;
        }
    }
       
?>