<?php
session_start();

$count = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    foreach ($_FILES['files']['name'] as $i => $name) {
        if (strlen($_FILES['files']['name'][$i]) > 1) {
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], 'uploads/'.$name)) {
                $count++;
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Upload Page Image Files</title>
        <link rel="stylesheet" href="css/Style.css">
    </head>
    
    <body>
       <?php echo $_SESSION['publicationId']; ?>
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
            if ($count > 0) {
                echo "<p class='msg'>{$count} files uploaded</p>\n\n";
            }
            ?>
            <h1>Page Image Files</h1>
            <p><b>Please select all image files for the publication.</b>
                You can select several files at once by clicking the first file
            in the group and holding the shift key while clicking the last file
            of the group.</p>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="files[]" id="files" multiple>
                <input class="button" type="submit" value="Upload" />
            </form>
            <p>After clicking the upload button, refer to the bottom of the page
            for upload progress.</p>
            <p>When the upload is complete <a href="ImportPublicationPart3.php">Continue to the next step</a></p>
        </div>
    </body>
</html>