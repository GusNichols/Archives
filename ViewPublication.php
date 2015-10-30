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
    <div class="nav">
     <!--Banner and navigation bar !-->
        <!--<img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">!-->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Publication</a></li>
            <li><a href="search.php">Search</a></li>
            <li>About</li>
        </ul>
    </div>
        <!--Banner and navigation bar !--> 
    <div class="container">
        <div class="bb-custom-wrapper">
            <div id="bb-bookblock" class="bb-bookblock">
            <?php 
                //get last page number
                $q=$pdo->prepare("select PageNumber from page where publication_publicationId=? order by PageNumber desc limit 1; ");
                $q->execute(array($_SESSION['publicationId'])); 
                $lastPage = $q->fetchColumn();
                
                $count=1; while($count==1){
                $sql= $pdo->prepare("SELECT ImagePath from Page WHERE Publication_PublicationId=? AND PageNumber=?");
                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath = $sql->fetchColumn();
                
                     
                ?>
          
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <!-- blank for first page to the right !-->
                        
                     </div> 
                     <div class="bb-custom-firstpage">
                         <img src="<?php echo $imagePath?>" height="635" width="525"  alt="Sheaf Page 1">	
                     </div>
                    
                </div>
           <?php $count++; } ?>
                <?php
                while(($count>1)&&($count<=$lastPage))
                {                

                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath2 = $sql->fetchColumn();
                 
                
                ?>
                
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <img src="<?php echo $imagePath2?>" height="635" width="525"  alt="Sheaf Page <?php echo $count?>">
                    </div> 
                    
                <?php
                
                $count++;
                $sql->execute(array($_SESSION['publicationId'], $count));
                $imagePath3 = $sql->fetchColumn();
                
                if($imagePath3==false)
                {
                   if($count<$lastPage)//if not all of the pages have been displayed yet
                   {   $testCount=$count+1; //see if next page exists
                       $sql->execute(array($_SESSION['publicationId'], $testCount));
                       $TestImagePath = $sql->fetchColumn();
                       //if next page does not exist either, assume main pages are finished...
                       if($TestImagePath==false)
                       {
                       $count=1000;//...and move on to supplement pages.
                       }
                       //otherwise, skip this page and keep going
                   }
                   
                   else //if last page has been reached
                   {
                       $count=0;//get out of while loop
                       
                   }
                }
                if($count!=0) //if there are still pages to display
                {
               ?>
               
                    <div class="bb-custom-side">
                         <img src="<?php echo $imagePath3?>" height="635" width="525"  alt="Sheaf Page <?php echo $count?>">	
                    </div>
                
                <?php }
                //if all pages have been displayed
                if($count==0){?> <div class="bb-custom-side"><h1>You have reached the end of this publication</h1></div>
                <?php } ?>
                </div>
                <?php $count++; }
              /*  */ ?>
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
