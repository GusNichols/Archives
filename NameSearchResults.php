<?php
session_start();
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";

try {
    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected successfully<hr>';
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
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <hr>
        <!--Banner and navigation bar !--> 
        <?php
            $personId= findName($_POST['lname'],$_POST['fname'],$pdo);

            echo "<p> The person's id in the database is: </p>".$personId;
        ?>

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
          return 2;
        }
        else
        {
         return $results;
        }
    }
       
?>