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

            

//TODO FIX escape strings ON OTHER FILES SO THAT SEARCH IS CONSISTANT (possibly done)
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
        <link rel="stylesheet" href="css/Style.css">
        <script src="js/modernizr.custom.js"></script>
</head>
<body>
    <div class="nav">
     <!--Banner and navigation bar commented out to make more room for result pages !-->
        <!--<img src="images/GusNicholsBanner.jpg" alt="Gus Nichols Archives Banner" height="79" width="1360">!-->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="ChoosePublicationToView.php">View Publications</a></li>
            <li><a href="importFile.php">Import Publication</a></li>
             <li><a href="search.php">New Search</a></li>
            <li>About</li>
            <li><a href="SearchResultsViewAll.php"> View All </a></li>
        </ul>
        <!--Banner and navigation bar !--> 
    </div>
        <?php  
        
        
        //TODO change for result table details to show on right side
        //TODO add page jump search
        
        
        if(!isset($_SESSION['SearchResults'])) //if new search is occuring
        {
            
            $_SESSION['personId']= findName(mysql_real_escape_string($_POST['lname']),
                    mysql_real_escape_string($_POST['fname']),$pdo);
            
            if($_POST['PubName']=='All')// if searching through all yearbooks in database
            {   
                $sql= $pdo->prepare("SELECT Page_PageId FROM PageInfo WHERE Person_PersonId=?");
                $sql->execute(array($_SESSION['personId']));
                $resultPageIds=$sql->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['row_count'] = $sql->rowCount();
              
            }
            else // if looking through a specific yearbook
            {
                //find publication id
                $sql=$pdo->prepare("SELECT PublicationId FROM Publication WHERE Name=?");
                $sql->execute(array($_POST['PubName']));
                $_SESSION['publicationId']=$sql->fetchColumn();
                
                $sql=$pdo->prepare("SELECT Page_PageId FROM PageInfo WHERE Person_PersonId=? && Publication_PublicationId=?");
                $sql->execute(array($_SESSION['personId'],$_SESSION['publicationId']));
                
                $resultPageIds=$sql->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['row_count'] = $sql->rowCount();
            }
            
            $desArray=[];
            array_push($desArray,""); //fills [0] space in array
            $typeArray=[];
            array_push($typeArray,""); //fills [0] space in array
            $pathArray=[];
            array_push($pathArray,""); //fills [0] space in array

            if ($_SESSION['row_count'] > 0) // if at least one result is returned
            {
                foreach($resultPageIds as $id)
                {        
                    $sql3= $pdo->prepare("SELECT Description, Type FROM PageInfo WHERE Page_PageId =? AND Person_PersonID=?");
                    $sql3->execute(array($id['Page_PageId'], $_SESSION['personId']));
                    // get page info for search result pages
                    $resultInfo=$sql3->fetch(PDO::FETCH_ASSOC); //fetch columns from database
                    $resultDes=$resultInfo[Description];
                    $resultType=$resultInfo[Type];
                    array_push($desArray,$resultDes); // add value to array with all resulting page info
                    array_push($typeArray,$resultType); 
                    $_SESSION['SearchDescriptions']=$desArray; //convert array to session array
                    $_SESSION['SearchTypes']=$typeArray;
                    //get image paths for search result pages
                    $sql2= $pdo->prepare("SELECT ImagePath FROM Page WHERE PageId=?");
                    $sql2->execute(array($id['Page_PageId']));
                    $resultPath=$sql2->fetch(PDO::FETCH_ASSOC);
                    $stringPath=array_pop($resultPath); //convert from array to string 
                    array_push($pathArray,$stringPath); // add value to array with all resulting paths
                    $_SESSION['SearchResults']=$pathArray; //image paths stored in session array
                        
                    
                    
                }
                    
            } 
            else 
            {

                echo "<div class='wrap'>Your search returned 0 results. <br> "
                . "<a href='search.php'>New Search</a></div>";

            }  
        }

if(isset($_SESSION['SearchResults']))//if search results have already been acquired 
{ 
    
?>

<div class="container">
<div class="bb-custom-wrapper">
    <div id="bb-bookblock" class="bb-bookblock">
        
   <?php ?>
        <?php $count=1;
        while($count<=$_SESSION['row_count'])
        {  
           
       
        ?>
         <div class="bb-item">
            <div class="bb-custom-side">
                <div class="wrap">
                <table>
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo "Publication:" . extractPublicationName($_SESSION['SearchResults'][$count]); ?><td>
                </tr>
                <tr>
                    <td><?php echo "Page Number:" . extractPageNumber($_SESSION['SearchResults'][$count]); ?></td>
                </tr>
                <tr>
                    <td><?php echo "Description:" . $_SESSION['SearchDescriptions'][$count]; ?></td>
                </tr>
                <tr>
                    <td><?php echo "Type:" . $_SESSION['SearchTypes'][$count]; ?></td>  
                </tr>
                </tbody>
                </table>
                </div>
            </div> 

        <?php

        //$count++;

        if($count<=$_SESSION['row_count'])
        {

       ?>

            <div class="bb-custom-firstpage">
                 <img src="<?php echo $_SESSION['SearchResults'][$count];?>" height="635" width="525"  alt="Result Page <?php echo $count?>">	
            </div>

        <?php }
        else{?> <div class="bb-custom-side"><h1>End of Results</h1></div>
        <?php } ?>
        </div>
        <?php $count++; 
        }
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

<?php } 

function findName($last, $first, $pdo)
    {
        if($last != NULL || "") //if searching with last name, sanitize data
        {
            $last = trim($last);
            $last = stripslashes($last);
            $last = htmlspecialchars($last);
        }
        if($first != NULL || "") // if searching with first name, sanitize data
        {
            $first = trim($first);
            $first = stripslashes($first);
            $first = htmlspecialchars($first);
        }
        //manual return for "unknown" entries (These have personId of 1)
       if($last === "Unknown")
        {
          return 1;
        }
       if(($last != NULL || "") && ($first != NULL || ""))//if searching with both first and last names
       {
        $stmt = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND FirstName=?");
        $stmt->execute(array($last, $first));
        $results = $stmt->fetchColumn();
        return $results;
       }
       
        if($first==NULL || "")//if only searching with last name
       {
        $stmt = $pdo->prepare("SELECT PersonId FROM Person WHERE LastName=? AND Firstname='' ");
        $stmt->execute(array($last));
        $results = $stmt->fetchColumn(); ////needs to use fetchAll(PDO::FETCH_ASSOC) eventually
        return $results;
       }
    }
    
    function extractPageNumber($imageName)
{
    $start  = strpos($imageName, '(');
    $end    = strpos($imageName, ')', $start + 3);
    $length = $end - $start;
    $pageNum = substr($imageName, $start + 4, $length - 4);
        return $pageNum;
}
   ///  TODO fix function!
   function extractPublicationName($imageName)
{
    $start  = strpos($imageName, '\\');
    $end    = strrpos($imageName, '\\');
    $length = $end - $start;
    $pubName = substr($imageName, $start+1, $length-1);
        return $pubName;
} //*/
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
