<!DOCTYPE html>
<?php
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


 $personId= findName($_POST['lname'],$_POST['fname'],$pdo);
 print($personId);

function test($a)
{
    echo $a;
}
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
<html>
    <head>
        <title>
    </title>
        <link rel="stylesheet" href="Style.css">
    </head>
</html>