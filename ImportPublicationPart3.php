<?php
session_start();
$publicationId=11; //temporary variable!!
$_SESSION['publicationId']=$publicationId;
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Connected successfully <hr>';
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
        <link rel="stylesheet" href="Style.css">
        <title></title>
    </head>
    <body>
        <div class="wrap">
            <?php
        foreach(glob('C:\MAMP\htdocs\GusNicholsArchives\uploads\*.jpg', GLOB_NOSORT) as $imagePath)   
        {  
        $imageName= basename($imagePath);
        $pageNum= extractPageNumber($imageName);
        $stmt = $pdo->prepare("SELECT PageId FROM Page WHERE PageNumber=? AND Publication_PublicationId=?");
        $stmt->execute(array($pageNum, $_SESSION['publicationId']));
        $pageId = $stmt->fetchColumn();
        if ($pageId)
            {
             //echo $pageNum.":".$pageId."<br />";
             $sql=$pdo->prepare("UPDATE Page SET Image_Path=? WHERE PageId=?");
             $sql->execute(array($imagePath,$pageId));
             echo "Page ".$pageNum."imported <br />";
            }
        
        else
            {
                echo "Page ".$pageNum."failed to import <br />";
            }
         
        }
            ?>
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