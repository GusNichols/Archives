<?php
session_start();
//---------------------------------------
//author: Lindsey Wells
/*description: Import process after second .csv file is obtained. The file is 
*opened and information is placed into the database.
 */
//---------------------------------------

    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //  echo 'Connected successfully <hr>';
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
        <title></title>
    </head>
    <body>
        <?php //echo $_SESSION['publicationId']; ?>
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
        //---start page table import---(working)---
    try
    {
    
    $publicationId= $_SESSION['publicationId'];
    $file2 = $_POST['file'];
    //echo $file2. "<br>";
    $fp2 = fopen($file2,'r');
    set_time_limit(0);
    $data2 = [];
    for($i=0; $i<5; $i++) //ignores first 5 lines
    {
        fgetcsv($fp2);
    }
    while (($data2 = fgetcsv($fp2)) !== FALSE)
    {
        
        $pageNumber = trim($data2[6]);
        
        /*/*If page number starts with S, change to S to equal 1000
            so the numbers continue as 1001, 1002 etc.*/
        // if part of supplement or pages moved to the end of the publication:
        if (stripos($pageNumber,'S') !== false) 
        { 
            $pageNumber = preg_replace('/[^0-9]/', '', $pageNumber); //take out letters (S)
            $pageNumber=(int)$pageNumber +1000; //add 1000 to supplement page number
        }
      
        //check for duplicate entries
        $q = $pdo->query("SELECT PageId FROM Page WHERE Publication_PublicationId='".$publicationId."' AND PageNumber='".$pageNumber."'");
        $duplicateRows= $q->rowCount();
        if(($duplicateRows)==0)
        {
           
            $sql3 = "INSERT INTO Page (PageNumber,Publication_PublicationId)
            VALUES ('".$pageNumber."','".$publicationId."')";
            $pdo->exec($sql3);
        }
    }
   // echo " Page numbers imported sucessfully. ";
}
catch (Exception $e)
    {
        echo $sql3 . "<br>" . $e->getMessage();
        fclose($file2);
    }
    
    
    //----end page table import------------*/
    
    //start PageInfo table import--(working)-- 
try
{
    rewind($fp2);
    $data3 = [];
    for($i=0; $i<5; $i++) //ignores first 5 lines
    {
       fgetcsv($fp2);
        
    }
     while (($data3 = fgetcsv($fp2)) !== FALSE)
    {  
        $lastName = trim($data3[0]);
        
        
        $firstName = trim($data3[1]);
        
        
        $personId  = findName($lastName, $firstName, $pdo);
        $page = trim($data3[6]);
     
        $pageId = findPage($publicationId, $page,  $pdo);
        $description = trim($data3[7]);
        
      
        $type = trim($data3[8]);
     
        // Insert Data
        $sql4 = $pdo->prepare("INSERT INTO PageInfo (Description,Type,Person_PersonId,Page_PageId,Publication_PublicationId)
        VALUES (?,?,?,?,?)");
        $sql4->execute(array($description,$type,$personId,$pageId,$publicationId));
        
    }
   // echo "all imports complete";
    fclose($file2);
    
}
catch (Exception $e)
{
    echo $sql4 . "<br>" . $e->getMessage();
    fclose($file2);
}
    
    //end PageInfo table import--*/

//functions section
function findName($last, $first, $pdo)
    {
       //personId specifically made for "unknown" names
       if($last === "Unknown")
        {
          return 1;
        }
       
        $stmt = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND (FirstName=? OR NickName=?)");
        $stmt->execute(array($last, $first, $first));
        $results = $stmt->fetchColumn();
        if ($results)
        {
          return $results;
        }
        else
        {
         return 2; //goes into exception group
        }
    }
function findPage($publication, $page, $pdo)
    {
    
        $stmt = $pdo->prepare("SELECT PageId FROM Page WHERE Publication_PublicationId=? AND PageNumber=?");
        $stmt->execute(array($publication, $page));
        $results = $stmt->fetchColumn();
         return $results;
    }
        $_SESSION['part']=3;
        ?>
        <div class="wrap">
        <p>All .csv files have been imported successfully. Please continue to 
            <a href="UploadPageFiles.php">the image importing process</a>. </p>
        </div>
    </body>
</html>
