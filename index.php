<?php
session_start();
    $connString = "mysql:host=localhost;dbname=GusNicholsArchives";
    $user ="root";
    $pass ="root";

try {
    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Connected successfully! <hr>';
    }
catch(PDOException $e)
    {
    echo 'Connection failed: ' . $e->getMessage();
    }
?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset=UTF-8>
        <link rel="stylesheet" href="css/Style.css">
        <title>Gus Nichols Archives</title>
    </head>
    <body>
        <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Yearbook</a></li>
            <li><a href="search.php">Search</a></li>
            <li>About</li>
        </ul>
        <hr>
        <!--Banner and navigation bar !--> 
        
        <!-- <p> Find a personId: </p>

        <form method='post' action='NameSearchResults.php'>
            <table>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='fname' required /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lname' required /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Search'>
                    </td>
                </tr>
            </table>
        </form>
        !-->
    </body>
</html>


