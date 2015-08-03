<?php
    $connString = "mysql:host=localhost;dbname=GusNicholsArchives";
    $user ="root";
    $pass ="root";

try {
    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected successfully! <hr>';
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
        <link rel="stylesheet" href="Style.css">
        <title>Gus Nichols Archives</title>
    </head>
    <body>
        <a href='ImportFile.php'>Import New Publication</a>
        <hr>
        <p>Find a personId:</p>
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
               
                    <td></td>
                    <td>
                        <input type='submit' value='Search'>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>


