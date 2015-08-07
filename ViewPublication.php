<?php
session_start();
$publicationId=11; //temporary variable!!
$_SESSION['publicationId']=$publicationId;
    $connString = "mysql:host=localhost;dbname=GusNicholsLibrary";
    $user ="root";
    $pass ="root";
error_reporting(E_ALL);
ini_set("auto_detect_line_endings",true);
/*
try {
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Connected successfully <hr>';
    }
catch(PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
    }
 * 
 */
?>

<!DOCTYPE html>
<html lang="en" class="no-js demo-4">
<head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>View Publication</title>
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/bookblock.css" />
        <!-- custom demo style -->
        <link rel="stylesheet" type="text/css" href="css/demo4.css" />
        <script src="js/modernizr.custom.js"></script>
</head>
<body>
     <!--Banner and navigation bar !-->
        <img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="100" width="1351">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li>View Publications</li>
            <li><a href="importFile.php">Import Publication</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
    <div class="container">
        <div class="bb-custom-wrapper">
            <div id="bb-bookblock" class="bb-bookblock">
            <?php $count=1; while($count==1){ ?>
                <div class="bb-item">
                    <div class="bb-custom-side">
                         <!-- blank for first page to the right !-->
                     </div> 
                     <div class="bb-custom-firstpage">
                         <img src="uploads/Sheaf 1979 (pg 1).jpg" height="665" width="525"  alt="pg 1">	
                     </div>
                    
                </div>
           <?php $count++; }
                
                while(($count>1)&&($count<=192))
                {   ?>
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <img src="uploads/Sheaf 1979 (pg <?php echo $count?>).jpg" height="665" width="525"  alt="pg 2">
                    </div>
                    <?php $count++; ?>
                    <div class="bb-custom-side">
                        <img src="uploads/Sheaf 1979 (pg <?php echo $count?>).jpg" height="665" width="525"  alt="pg 3">
                    </div>
                </div>
                <?php $count++; } ?>
                <!--
                <div class="bb-item">
                    <div class="bb-custom-side">
                            <p>Croissant pudding gingerbread gummi bears marshmallows </p>
                    </div>
                    <div class="bb-custom-side">
                            <p>Wafer donut caramels chocolate caramels sweet roll.</p>
                    </div>
                </div>
                !-->

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
                                        speed : 1000,
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