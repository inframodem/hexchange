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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href = "listing.css">
  <title>Listings</title>
  <?php
  require_once 'navbar.php';
  $listingid = "";
  //gets id from get method
  if(isset($_GET["id"])){
    $listingid = intval($_GET["id"]);
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT listingTitle FROM listing WHERE idListing = ?";
      $stmt->prepare($query);
      $stmt->bind_param("i",$listingid);
      $stmt->execute();
      $stmt->bind_result($listtitle);
      $stmt->fetch();
      //sets tab title and heading title to the title of the listing
      echo "<h1>". $listtitle. "</h1> <br>";
      echo "<title> Listing: ". $listtitle. "</title> <br>";
    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
  }

  ?>

</head>
<body>
  <div class = "imageholder">
    <?php
    //gets image path from db and displays it on listing page
    if(isset($_GET["id"])){
      $listingid = $_GET["id"];
      $stmt = $conn->stmt_init();
      $query = "SELECT lsImage
      FROM listing
      WHERE idListing = ?";
      $stmt->prepare($query);
      $stmt->bind_param("i",$listingid);
      $stmt->execute();
      $stmt->bind_result($pimage);
      $stmt->fetch();
      echo "<img src=".$pimage.">";
    }

     ?>
  </div>
  <div class = "DescHolder">
    <h2>Description</h2><br>
    <?php
    //gets and displays listing description
    $listingid = "";
    if(isset($_GET["id"])){
      $listingid = $_GET["id"];
      try{
        $stmt = $conn->stmt_init();
        $query = "SELECT listingDesc FROM listing WHERE idListing = ?";
        $stmt->prepare($query);
        $stmt->bind_param("i",$listingid);
        $stmt->execute();
        $stmt->bind_result($listdesc);
        $stmt->fetch();
        //adds new lines and removes slashes
	$descbetter = preg_replace('/\v+|\\\r\\\n/Ui','<br/>',$listdesc);
	$descbetter = stripslashes($descbetter);

        echo "<p>".$descbetter."</p>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </div>
  <?php
  //gets listing username and links to a read only profile page
    $stmt = $conn->stmt_init();
    $query = "SELECT u.userName FROM users u
    INNER JOIN listinguser lu ON lu.idUsers = u.idUsers
    INNER JOIN listing l ON lu.idListing = l.idListing WHERE l.idListing = ?";
    $stmt->prepare($query);
    $stmt->bind_param("i",$listingid);
    $stmt->execute();
    $stmt->bind_result($listUser);
    $stmt->fetch();
    echo "<div class = listinguser>.<a href = 'profileView.php?userpage=".$listUser."'> User: ".$listUser."</a></div>"
   ?>
  <div class = "Specs">
    <ul>
    <?php
    $listingid = "";
    if(isset($_GET["id"])){
      $listingid = $_GET["id"];
      try{
        //gets listing information and displays it
        $stmt = $conn->stmt_init();
        $query = "SELECT listingDate,bestByDate,city,county,state,address1,address2 FROM listing WHERE idListing = ?";
        $stmt->prepare($query);
        $stmt->bind_param("i",$listingid);
        $stmt->execute();
        $stmt->bind_result($listingDate,$bestByDate,$city,$county,$state,$address1,$address2);
        $stmt->fetch();
        echo "<li> Date Submitted: ".$listingDate." &nbsp;&nbsp;&nbsp;Best by Date: ".$bestByDate.
        " &nbsp;&nbsp;&nbsp;City: ".$city." &nbsp;&nbsp;&nbsp;County: ".$county." &nbsp;&nbsp;&nbsp;State: ".$state."</li>
        <li>Address:</li><li>".$address1."</li><li>".$address2."</li>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </ul>
  </div>
  <div class = "Produce">
    <table>
      <tr>
        <th>Produce Name</th>
        <th>Produce Type</th>
        <th>Produce Amount</th>
        <th>Unit of Measurement</th>
      </tr>
      <?php
      //gets produce information and displays it
      if(isset($_GET["id"])){
        $listingid = $_GET["id"];
        try{
          $stmt = $conn->stmt_init();
          $query = "SELECT p.produceName,p.produceType,p.measurementValue,p.measurementType
          FROM produce p INNER JOIN listingproduce lp ON p.idProduce = lp.idProduce
          INNER JOIN listing l ON lp.idListing = l.idListing WHERE l.idListing = ?";
          $stmt->prepare($query);
          $stmt->bind_param("i",$listingid);
          $stmt->execute();
          $stmt->bind_result($produceName,$produceType,$measuremntValue,$measurmentType);


          while($stmt->fetch()){
		echo "<tr>";
            echo '<td>'.$produceName.'</td>';
            echo '<td>'.$produceType.'</td>';
            echo '<td>'.$measuremntValue.'</td>';
            echo '<td>'.$measurmentType.'</td>';
		echo"</tr>";
          }

        }
        catch(mysqli_sql_exception $excep){
          echo "Database error:" . $conn->error;
          throw $excep;
        }
      }
      ?>
    </table>
  </div>

</body>
</html>
