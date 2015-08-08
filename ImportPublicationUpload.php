<?php
session_start();
?>
<!DOCTYPE html>
<?php
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
  <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/Style.css">
        <title>Gus Nichols Archives Add Publication</title>
    </head>
    <body>
        <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ViewPublication.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
    <?php
    
    // Where the file is going to be placed C:\Users\Lindsey\Documents\NetBeansProjects\GusNicholsArchives\
$main_path = "uploads\\";

$target_path = $main_path . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
    {
    echo "The file has been uploaded sucessfully. It is now ready to be imported into the database.<br>"; 
    
    if($_SESSION['part']==2)
     {
       ?> 
        <form action='ImportPublicationPart2.php' method='post'>
        <input type='hidden' name='file' value='<?php echo $target_path ?>'>
        <p>This can take several more minutes. Please do not close this page until the import is complete.</p>
        <input type='submit' value='Import Publication'>
        </form> 
    <?php }
    else
    { ?> 
        <form action='ImportPublicationPart1.php' method='post'>
        <input type='hidden' name='file' value='<?php echo $target_path ?>'>
        <p>Name of publication:</p><input type="text" name="name" required>
        <p>This process could take several minutes. Please do not close this page.</p>
        <input type='submit' value='Import Publication'>
        </form>
   <?php 
    }
   }
else
    {  
       
       switch ($_FILES['uploadFile']['error'])
        {
          case 1: echo "file too big.. issue with php";
              break;
          case 2: echo "file too big.. issue with form";
              break;
          case 3: echo "file did not finish uploading";
              break;
          case 4: echo "none of the file uploaded";
              break;
          default: echo "An Error has occured.";
        }
    }
    
    ?>
        
    </body>
</html>