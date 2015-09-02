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
     <!--Banner and navigation bar !-->
        <!--<img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">!-->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Publication</a></li>
             <li><a href="search.php">New Search</a></li>
            <li>About</li>
        </ul>
        <!--Banner and navigation bar !--> 
    <div class="container">
        <div class="bb-custom-wrapper">
            <div id="bb-bookblock" class="bb-bookblock">
                <?php  $personId= findName($_POST['lname'],$_POST['fname'],$pdo);
                $sql= $pdo->prepare("SELECT Page_PageId FROM result WHERE Person_PersonId=?");
                $sql->execute(array($personId));
                $resultPageIds=$sql->fetchAll(PDO::FETCH_ASSOC);
                $row_count = $sql->rowCount();
                $pathArray=[];
                array_push($pathArray,"");
                if ($row_count > 0) 
                 {
                    foreach($resultPageIds as $id)
                    {
                        //echo $id[Page_PageId]."<br>";
                        $sql2= $pdo->prepare("SELECT Image_Path FROM Page WHERE PageId=?");
                        $sql2->execute(array($id['Page_PageId']));
                        $resultPath=$sql2->fetch(PDO::FETCH_ASSOC);
                        $shortPath=str_replace("C:\\MAMP\\htdocs\\GusNicholsArchives\\", "", $resultPath[Image_Path]);
                        array_push($pathArray,$shortPath);
                        
                        
                    }

                 } 
                else 
                {
                    echo "Your search returned 0 results.";
                }  


                
                ?>
                
          
                <div class="bb-item">
                    <div class="bb-custom-side">
                        <!-- blank for first page to the right !-->
                     </div> 
                     <div class="bb-custom-firstpage">
                         <!--<h1><?php print_r($pathArray); ?>  </h1>!-->
                         <img src="<?php echo $pathArray[1]; ?>" height="635" width="525"  alt="Sheaf Page 1">	
                     </div>
                    
                </div>
           <?php ?>
                <?php
                while(($count>1))
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
                if($imagePath3==false)
                {
                   $count='';
                }
                if($count!='')
                {
               ?>
               
                    <div class="bb-custom-side">
                         <img src="<?php echo $newPath3?>" height="635" width="525"  alt="Sheaf Page <?php echo $count?>">	
                    </div>
                
                <?php }
                if($count==''){?> <div class="bb-custom-side"><h1>End of Results</h1></div>
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
    <?php function findName($last, $first, $pdo)
    {
        //manual return since it can't find personID 1 for "unknown" entries
       if($last === "Unknown")
        {
          return 1;
        }
       
        $stmt = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND FirstName=?");
        $stmt->execute(array($last, $first));
        $results = $stmt->fetchColumn();
        if (!$results)
        {
          return 2; // number for exceptions that couldn't import properly
        }
        else
        {
         return $results;
        }
    }
    ?>
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
