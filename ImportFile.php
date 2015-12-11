<?php
session_start();
//---------------------------------------
//author: Lindsey Wells
/*description: The first file in the import process where a .csv is obtained 
 * from the user. This file is returned to in order to get the second .csv file.
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
        <title>Gus Nichols Archives Add Publication</title>
    </head>
    <body>
        <div class="nav">
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
        </div> 
        <div class="wrap">
        <h4>**Important**<br>Please ensure that you import .CSV files <b>NOT</b> Excel files.</h4>
        <br>
       
        
        <?php if($_SESSION['part']==2){ //if the first file has already been uploaded and imported
        echo "<h1> Step 2 - File Upload:</h1>"; ?>
        <br>
        <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        
        <p>Please choose the .csv file from the <b>first</b> sheet of the excel worksheet.
            <br>This file contains names, page numbers, descriptions, and types. </p>
        <p>file to upload:</p> <input type="file" name="uploadedfile" required><br> 
        <input type="submit" value="Upload File" />
        </form>
        
        <?php } else { $_SESSION['part']=1; //create session variable since in this case it doesn't exist
        echo "<h1> Step 1 - File Upload:</h1>"; ?>
        <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        
        <p>Please choose the .csv file from the <b>second</b> sheet of the excel worksheet.
        <br>This file contains names and page numbers.</p>
        <p>file to upload:</p> <input type="file" name="uploadedfile" required><br> 
        <p>Name of publication:</p><input type="text" name="name" required>
        <input type="submit" value="Upload File" />
        </form>
        <?php  }//end else 
        
        if($_SESSION['part']==1 && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            //check for correct file type
            $info = pathinfo($_FILES['uploadedfile']['name']);
            if($info['extension'] != 'csv')
                {
                    echo "<span class='error'>Only .csv files allowed.</span>";
                }
            else //if correct, check if name is valid
            {
                $name = $_POST["name"];
                $name = trim($name);
                $name = stripslashes($name);
                $name = htmlspecialchars($name);
                $q = $pdo->query("SELECT PublicationId FROM Publication WHERE Name='".$name."' ");
                $duplicateRows= $q->rowCount();

                if($duplicateRows==0) //if name does not already exist
                {
                    $_SESSION['name']=$name;
                    mkdir("uploads\\".$_SESSION['name']."\\");
                    $main_path = "uploads\\".$_SESSION['name']."\\";
                    $target_path = $main_path . basename( $_FILES['uploadedfile']['name']);
                    
                    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
                    { ?>
                        <p>The file has been uploaded successfully. <br>
                        It is now ready to be imported into the database.</p>
                        <form action='ImportPublicationPart1.php' method='post'>
                        <input type='hidden' name='file' value='<?php echo $target_path ?>'>
                        <p>This process could take several minutes. Please do not close or resubmit this page.</p>
                        <input type='submit' value='Import Publication'>
                        </form>
              <?php } else{ echo "<span class='error'>An error has occured while uploading the file.</span>";}

                }
                else if($duplicateRows>0) //if name already exists in the database
                {
                    echo "<span class='error'>This publication name already exists.</span>";
                }
            }
        }
       
        
        
        if($_SESSION['part']==2 && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            //check for correct file type
            $info = pathinfo($_FILES['uploadedfile']['name']);
            if($info['extension'] != 'csv')
                {
                    echo "<span class='error'>Only .csv files allowed.</span>";
                }
            else //if file type is correct, continue
            {       
                    $main_path = "uploads\\".$_SESSION['name']."\\";
                    $target_path = $main_path . basename( $_FILES['uploadedfile']['name']);
                    
                    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
                    { ?>
                        <p>The file has been uploaded successfully.</p>
                        <form action='ImportPublicationPart2.php' method='post'>
                        <input type='hidden' name='file' value='<?php echo $target_path ?>'>
                        <p>Ready to continue. Please do not close or resubmit this page 
                            after the clicking the button.</p>
                        <input type='submit' value='Continue'>
                        </form>
              <?php } else{ echo "<span class='error'>An error has occured while uploading the file.</span>";}
            }
        }
        
?>
       
       
       
        </div>
    </body>
</html>
