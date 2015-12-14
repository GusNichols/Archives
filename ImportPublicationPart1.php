<?php
session_start();
//---------------------------------------
//author: Lindsey Wells
//description: Import process file #2. A publication is created and the file is 
// read to place names into the database.
//---------------------------------------
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //  echo 'Connected successfully <br>';
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
   <title>Importing a New Publication</title>
</head>
<body>
    <div class="nav">
    <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            
        </ul>
        <!--Banner and navigation bar !--> 
    </div>

 <?php
   // start Publication table import  ---------------
  
try {
        $name= $_SESSION['name'];
        $sql = "INSERT INTO Publication (Name)
        VALUES ('$name')";
        $pdo->exec($sql);
    
        $stmt = $pdo->prepare("SELECT PublicationId FROM Publication WHERE Name=?");
        $stmt->execute(array($name));
        $publicationId = $stmt->fetchColumn(); //get autogenerated id from database
        $_SESSION['publicationId']=$publicationId;
       // echo " <p>New Publication record created successfully.</p> <br> ";
    } 

catch (Exception $e) 
    {
        echo $sql . "<br>" . $e->getMessage();
       
    }
    
    //end Publication table import------------------

    //----start person table import ----
try
{
    
    $file =$_POST['file']; //uploaded csv file
    $fp = fopen($file,'r');
    set_time_limit(0); //prevents it from stopping before the process is complete
    
    $data = [];
    for($i=1; $i<5; $i++) //ignores first 5 lines of file
    {
        fgetcsv($fp);
    }
    while (($data = fgetcsv($fp)) !== FALSE)
    {   
        $lastName = trim($data[0]);
        $firstName = trim($data[1]);
        $nickname = trim($data[2]);
        $middleName = trim($data[3]);
        $prefix = trim($data[4]);
        $suffix = trim($data[5]);
        
        //check for duplicate entries
        $q = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND FirstName=?");
        $q->execute(array($lastName,$firstName));
        $duplicateRows= $q->rowCount();
        if(($duplicateRows)==0) //if name is not already in database
        {
        // Insert Data
            $sql2=$pdo->prepare("INSERT INTO Person (LastName,FirstName,NickName,MiddleName,Prefix,Suffix )
            VALUES (?,?,?,?,?,?)");
            $sql2->execute(array($lastName,$firstName,$nickname,$middleName,$prefix,$suffix));
        }
    }
  //  echo " <p>Person table populated sucessfully.</p><hr> ";
    
    fclose($file);
}
catch (Exception $e)
    {
        echo $sql2 . "<br>" . $e->getMessage();
        fclose($file);
    }
//------end person table import --------
    $_SESSION['part']=2;
    
    ?>
        <div class="wrap">
            <a href="ImportFile.php">Upload the next file</a>
        </div>
</body>
</html>


