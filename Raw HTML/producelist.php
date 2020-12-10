<?php
session_start();
if(isset($_SESSION['LAST_ACTIVITY']) && (time()- $_SESSION['LAST_ACTIVITY'] > 7200)){
  session_unset();
  session_destroy();
}
else{
  $_SESSION['LAST_ACTIVITY'] = time();
}
require_once 'config.inc.php';
 ?>
<head>
  <meta charset="utf-8">
  <?php
  require_once 'navbar.php';


  ?>
</head>
<body>
  <?php

    $states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');
  $genpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"\s]";


   ?>
 <div class = "listingfilter">
  <form method = "get" action=<?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
    Select State:<select id="statefilter" name="statefilter">
    <?php foreach ($states as $abr):
      echo('<option value = "'. $abr. '">'. $abr.'</option>');
        endforeach; ?>
     </select>
     City: <input type="text" id="cityfilter" name="cityfilter" maxlength="255">
     County: <input type="text" id="countyfilter" name="countyfilter" maxlength="255">
     <input type='submit' value="Submit">
   </form>
  </div>

  <div class = "listinglist">
   <?php
   $page = 0;
   $state = "";
   $city = "";
   $county = "";
   if(isset($_GET['currpage'])){
     $page += intval($_GET['currpage']);
   }
   if(isset($_GET['statefilter']) && preg_match($genpattern, $_GET['statefilter']) &&
   strlen($_GET['statefilter']) <= 255){
     $state =  $_GET['statefilter'];
   }
   if(isset($_GET['cityfilter']) && preg_match($genpattern, $_GET['cityfilter']) &&
   strlen($_GET['cityfilter']) <= 255){
     $city =  $_GET['cityfilter'];
   }
   if(isset($_GET['countyfilter']) && preg_match($genpattern, $_GET['countyfilter']) &&
   strlen($_GET['countyfilter']) <= 255){
     $county =  $_GET['countyfilter'];
   }

    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT IdListing, listingTitle, listingDate, bestByDate, city, state FROM listing
      WHERE state = ? OR  city = ? OR county = ?
      ORDER BY listingDate LIMIT 20 OFFSET ?";
      $stmt->prepare($query);
      $offset = 20 * $page;
      $stmt->bind_param("sssi",$state,$city,$county,$offset);
      $stmt->execute();
      $stmt->bind_result($qlistId,$qlistTitle,$qlistDate,$qBestByDate,$qCity,$state);
      echo "<ul>";
        while($stmt->fetch()){
          echo "<li><a href='listing.php?id=". $qlistId. "'>".$qlistTitle.'</a>'. "   "
          . $qlistDate. "   ". $qBestByDate."   ". $qdate."   ".$state;
        }
      echo "</ul>";

    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
    $conn->close();
    if($page > 0){
      echo '<a href="producelist.php?currpage='.($page - 1) .'">'.'Previous</a>';
    }
    echo '<a href="producelist.php?currpage='.($page + 1) .'">'.'Next</a>';

    ?>
  </div>
</body>
</html>
