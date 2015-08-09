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
        <h3>Part 1 or 3 - File Upload:</h3>
        <br>
        <h4>**Important**<br>Please ensure that you import .CSV files <b>NOT</b> Excel files.</h4>
        <br>
       <form enctype="multipart/form-data" action="ImportPublicationUpload.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
        
        <?php if($_SESSION['part']==2){ ?>
        <p>Please choose the .csv file from the <b>first</b> sheet of the excel worksheet.
            <br>This file contains names, page numbers, descriptions, and types. </p> 
        <?php } else { ?>
        <p>Please choose the .csv file from the <b>second</b> sheet of the excel worksheet.
            <br>This file contains names and page numbers.</p>
        <?php } ?>
        
        <p>file to upload:</p> <input name="uploadedfile" type="file" required><br>
        <input type="submit" value="Upload File" />
        </form>
        </div>
    </body>
</html>
