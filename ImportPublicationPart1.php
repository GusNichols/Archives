<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="Style.css">
   <title>Importing a New Publication</title>
</head>
<body>
<?php
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Connected successfully <br>';
    }
catch(PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
        
    }
 
   // start Publication table import (working) ---------------
     $name=$_POST['name'];
try {
        $sql = "INSERT INTO Publication (Name)
        VALUES ('$name')";
        $pdo->exec($sql);
    
        $stmt = $pdo->prepare("SELECT PublicationId FROM Publication WHERE Name=?");
        $stmt->execute(array($name));
        $publicationId = $stmt->fetchColumn();
        echo " New Publication record created successfully. <br> ";
    } 

catch (Exception $e) 
    {
        echo $sql . "<br>" . $e->getMessage();
       
    }
    
    //end Publication table import------------------*/

    //----start person table import (working)----
try
{
    
    $file =$_POST['file'];
    $fp = fopen($file,'r');
    set_time_limit(0);
    
    $data = [];
    for($i=0; $i<4; $i++) //ignores firt 5 lines
    {
        fgetcsv($fp);
    }
    while (($data = fgetcsv($fp)) !== FALSE)
    {
        $lastName = trim($data[0]);
        $firstName = trim($data[1]);
        //check for duplicate entries
        $q = $pdo->query("SELECT * FROM Person WHERE LastName='".$lastName."' AND FirstName='".$firstName."'");
        $duplicateRows= $q->rowCount();
        if(($duplicateRows)==0)
        {
        // Insert Data
            $sql2 = "INSERT INTO Person (LastName,FirstName)
            VALUES ('".$lastName."','".$firstName."')";
            $pdo->exec($sql2);
        }
    }
    echo " Person table populated sucessfully.<hr> ";
    
    fclose($file);
}
catch (Exception $e)
    {
        echo $sql2 . "<br>" . $e->getMessage();
        fclose($file);
    }
//------end person table import --------*/
    $_SESSION['part']=2;
    $_SESSION['publicationId']=$publicationId;
    ?>
    <form action='ImportFile.php' method='post'>
        <p>Please continue to the second part of the import process.</p>
        <input type='submit' value='Upload the next file'>
        </form>
</body>
</html>


