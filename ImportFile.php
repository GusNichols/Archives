<?php
session_start();
?>
<!DOCTYPE html>
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
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li>About</li>
        </ul>
        <hr>
        <!--Banner and navigation bar !-->
        
        <div class="wrap">
        <h4>**Important**<br>Please ensure that you import .CSV files <b>NOT</b> Excel files.</h4>
        <br>
       
        
        <?php if($_SESSION['part']==2){ //if the first file has already been uploaded and imported
        echo "<h3> Step 2 - File Upload:</h3>"; ?>
        <br>
        <form enctype="multipart/form-data" action="ImportPublicationUpload.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
        <p>Please choose the .csv file from the <b>first</b> sheet of the excel worksheet.
            <br>This file contains names, page numbers, descriptions, and types. </p>
        <p>file to upload:</p> <input name="uploadedfile" type="file" required><br>
        <input type="submit" value="Upload File" />
        </form>
        
        <?php } else { $_SESSION['part']=1; //create session variable since in this case it doesn't exist
        echo "<h3> Step 1 - File Upload:</h3>"; ?>
        <form enctype="multipart/form-data" action="ImportPublicationUpload.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
        <p>Please choose the .csv file from the <b>second</b> sheet of the excel worksheet.
        <br>This file contains names and page numbers.</p>
        <p>file to upload:</p> <input name="uploadedfile" type="file" required><br>
        <p>Name of publication:</p><input type="text" name="name" required>
        <input type="submit" value="Upload File" />
        </form>
        <?php  }//end else ?>
       
       
       
        </div>
    </body>
</html>
