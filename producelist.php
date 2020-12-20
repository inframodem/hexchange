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
  <link rel="stylesheet" href = "listinglist.css">
  <meta charset="utf-8">
  <?php
  require_once 'navbar.php';


  ?>
</head>
<body>
  <?php
//states array
    $states = array('','AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');
  $genpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"\s]";

//form for the filtering bar
   ?>
 <div class = "listingfilter">
  <form method = "get" action=<?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
    <label for="statefilter">Select State: </label><select id="statefilter" name="statefilter">
    <?php foreach ($states as $abr):
      echo('<option value = "'. $abr. '">'. $abr.'</option>');
        endforeach; ?>
     </select>
     <label for="cityfilter">City: </label><input type="text" id="cityfilter" name="cityfilter" maxlength="255">
     <label for="countyfilter">County: </label><input type="text" id="countyfilter" name="countyfilter" maxlength="255">
     <input type='submit' value="Submit">
   </form>
  </div>

  <div class = "listinglist">
   <?php
   $page = 0;
   $state = NULL;
   $city = NULL;
   $county = NULL;
   if(isset($_GET['currpage'])){
     $page += intval($_GET['currpage']);
   }
   if(isset($_GET['statefilter']) && strlen($_GET['statefilter']) <= 255){
     $state =  $conn->real_escape_string($_GET['statefilter']);
   }
   if(isset($_GET['cityfilter']) && strlen($_GET['cityfilter']) <= 255){
     $city =  $conn->real_escape_string($_GET['cityfilter']);
   }
   if(isset($_GET['countyfilter']) && strlen($_GET['countyfilter']) <= 255){
     $county =  $conn->real_escape_string($_GET['countyfilter']);
   }
//gets listings based off of whats input as filters sorted by newest on inet_ntop
//if a field is blank it displays all of that field
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT IdListing, listingTitle, listingDate, bestByDate, city, state FROM
	listing WHERE ((state like ?) or (? is null) or (? ='')) AND ((city like ?) or
	(? is null) or (? ='')) AND ((county like ?) or (? is null) or (? ='')) ORDER BY listingDate DESC LIMIT 20 OFFSET ?";
      $stmt->prepare($query);
      $offset = 20 * $page;
      $stmt->bind_param("sssssssssi",$state,$state,$state,$city,$city,$city,$county,$county,$county,$offset);
      $stmt->execute();
      $stmt->bind_result($qlistId,$qlistTitle,$qlistDate,$qBestByDate,$qCity,$state);
      echo "<ul>";
        while($stmt->fetch()){
          echo "<li><a href='listing.php?id=". $qlistId. "'>".$qlistTitle.'</a>'. "   Date Submitted: "
          . $qlistDate."     Best by Date: ". $qBestByDate."   City: ". $qCity."   State: ".$state;
        }
      echo "</ul>";

    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }

        ?>

  </div>
  <div class="pageArrows">


  <?php
  //adds page arrows if listings exceed the 20 limit
  $stmt = $conn->stmt_init();
  $query = "SELECT COUNT(IdListing) FROM listing";
  $stmt->prepare($query);
  $stmt->execute();
  $stmt->bind_result($listingcount);
  if($page > 0){
    echo '<a href="producelist.php?currpage='.($page - 1) .'">'.'Previous  </a>';
  }
  if(($page + 1 ) * 20 < $listingcount ){
  echo '<a href="producelist.php?currpage='.($page + 1) .'">'.'Next  </a>';
}
    $conn->close();
  ?>
  </div>
</body>
</html>
