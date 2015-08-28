<?php
session_start();
//echo session_status();
    //echo print_r($_SESSION);
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);

try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo 'Connected successfully <hr>';
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
        <?php 
        echo $_SESSION['publicationId']; ?>
        <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
        <div class="wrap">
            <?php
    if($_SESSION['part']==3)
    {
        foreach(glob("C:\\MAMP\\htdocs\\GusNicholsArchives\\uploads\\".$_SESSION['name'].
                "\\*.jpg", GLOB_NOSORT) as $imagePath)        
        {  
            $imageName= basename($imagePath);
           // echo $imageName;
            $pageNum= extractPageNumber($imageName);
           // echo $pageNum;
            $stmt = $pdo->prepare("SELECT PageId FROM Page WHERE PageNumber=? AND Publication_PublicationId=?");
            $stmt->execute(array($pageNum, $_SESSION['publicationId']));
            $pageId = $stmt->fetchColumn();
            if ($pageId)
            {
             //echo $pageNum.":".$pageId."<br />";
             $sql=$pdo->prepare("UPDATE Page SET Image_Path=? WHERE PageId=?");
             try{
             $sql->execute(array($imagePath,$pageId));
             echo "Page ".$pageNum."imported <br />"; }
            catch(PDOException $e)
            {
                echo 'Error: ' . $e->getMessage();
            }
            }
        
            else
            {
                $sql=$pdo->prepare("INSERT into Page (PageNumber,Image_Path, Publication_PublicationID)"
                        . "VALUES(?,?,?)");
                try{
                $sql->execute(array($pageNum,$imagePath,$_SESSION['publicationId']));
                echo "Page ".$pageNum." created and imported <br />";
                
                }
                catch(Exception $e)
                {
                    echo 'Error: ' . $e->getMessage();
                }
            }
            
        } //end foreach        
    } //end if          
                //session_unset(); //erase temporary values used for importing
            ?>             
        
            <h1>Congratulations! This Yearbook is ready to be viewed.</h1>
    
        </div>
        
    </body>
</html>
<?php
function extractPageNumber($imageName)
{
    $start  = strpos($imageName, '(');
    $end    = strpos($imageName, ')', $start + 3);
    $length = $end - $start;
    $pageNum = substr($imageName, $start + 4, $length - 4);
        return $pageNum;
}
?>