<?php
session_start();
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);

try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo 'Connected successfully <hr>';
    }
catch(PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
    }
$stmt=$pdo->prepare("SELECT PublicationId FROM Publication WHERE Name=?");
$stmt->execute(array($_POST['Name']));
$_SESSION['publicationId']=$stmt->fetchColumn();
$lastPage=192;
?>

<!DOCTYPE html>
<html lang="en" class="no-js demo-4">
<head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>View Publication</title>
        <meta name="author" content="Codrops" />
        <!-- <link rel="shortcut icon" href="../favicon.ico">!-->
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/bookblock.css" />
        <link rel="stylesheet" type="text/css" href="css/demo4.css" />
        <script src="js/modernizr.custom.js"></script>
</head>
<body>
     <!--Banner and navigation bar !-->
        <!--<img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">!-->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Publication</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
    <div class="container">
        <div class="bb-custom-wrapper">
            <div id="bb-bookblock" class="bb-bookblock">
            <?php $count=1; while($count==1){
                $sql= $pdo->prepare("SELECT Image_Path from Page WHERE Publication_PublicationId=? AND PageNumber=?");
                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath = $sql->fetchColumn();
                $newPath=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $imagePath);
                     
                ?>
          
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <!-- blank for first page to the right !-->
                     </div> 
                     <div class="bb-custom-firstpage">
                         <img src="<?php echo $newPath?>" height="635" width="525"  alt="Sheaf Page 1">	
                     </div>
                    
                </div>
           <?php $count++; } ?>
                <?php
                while(($count>1)&&($count<=$lastPage))
                {                

                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath2 = $sql->fetchColumn();
                $newPath2=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $imagePath2); 
                
                ?>
                
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <img src="<?php echo $newPath2?>" height="635" width="525"  alt="Sheaf Page <?php echo $count?>">
                    </div> 
                    
                <?php
                
                $count++;
                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath3 = $sql->fetchColumn();
                $newPath3=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $imagePath3); 
               
               ?>
               
                    <div class="bb-custom-side">
                         <img src="<?php echo $newPath3?>" height="635" width="525"  alt="Sheaf Page <?php echo $count?>">	
                    </div>
                </div>
                <?php $count++; }
                if($count>$lastPage){echo "<h1>You have reached the end of this publication</h1>";}?>
            </div>

                <nav>
                    <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
                    <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
                    <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
                    <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
                </nav>
        </div>
    

    </div><!-- /container -->
    
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
            <script src="js/jquerypp.custom.js"></script>
            <script src="js/jquery.bookblock.js"></script>
            <script>
                var Page = (function() {

                    var config = {
                        $bookBlock : $( '#bb-bookblock' ),
                        $navNext : $( '#bb-nav-next' ),
                        $navPrev : $( '#bb-nav-prev' ),
                        $navFirst : $( '#bb-nav-first' ),
                        $navLast : $( '#bb-nav-last' )
                            },
                        init = function() {
                                config.$bookBlock.bookblock( {
                                        speed : 1500,
                                        shadowSides : 0.8,
                                        shadowFlip : 0.4
                                } );
                                initEvents();
                        },
                        initEvents = function() {

                            var $slides = config.$bookBlock.children();

                            // add navigation events
                            config.$navNext.on( 'click touchstart', function() {
                                    config.$bookBlock.bookblock( 'next' );
                                    return false;
                            } );

                            config.$navPrev.on( 'click touchstart', function() {
                                    config.$bookBlock.bookblock( 'prev' );
                                    return false;
                            } );

                            config.$navFirst.on( 'click touchstart', function() {
                                    config.$bookBlock.bookblock( 'first' );
                                    return false;
                            } );

                            config.$navLast.on( 'click touchstart', function() {
                                    config.$bookBlock.bookblock( 'last' );
                                    return false;
                            } );

                            // add swipe events
                            $slides.on( {
                                    'swipeleft' : function( event ) {
                                            config.$bookBlock.bookblock( 'next' );
                                            return false;
                                    },
                                    'swiperight' : function( event ) {
                                            config.$bookBlock.bookblock( 'prev' );
                                            return false;
                                    }
                            } );

                            // add keyboard events
                            $( document ).keydown( function(e) {
                                    var keyCode = e.keyCode || e.which,
                                            arrow = {
                                                    left : 37,
                                                    up : 38,
                                                    right : 39,
                                                    down : 40
                                            };

                                    switch (keyCode) {
                                            case arrow.left:
                                                    config.$bookBlock.bookblock( 'prev' );
                                                    break;
                                            case arrow.right:
                                                    config.$bookBlock.bookblock( 'next' );
                                                    break;
                                    }
                                } );
                        };

                        return { init : init };

                    })();
            </script>
            <script>
                            Page.init();
            </script>
	</body>
</html>
