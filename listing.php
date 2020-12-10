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
  <?php
  require_once 'navbar.php';
  $listingid = "";
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

  <div class = "DescHolder">
    <h2>Description</h2><br>
    <?php
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
        echo "<p>".preg_replace('/\v+|\\\r\\\n/Ui','<br/>',$listdesc)."</p>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </div>
  <div class = "Specs">
    <ul>
    <?php
    $listingid = "";
    if(isset($_GET["id"])){
      $listingid = $_GET["id"];
      try{
        $stmt = $conn->stmt_init();
        $query = "SELECT listingDate,bestByDate,city,county,state FROM listing WHERE idListing = ?";
        $stmt->prepare($query);
        $stmt->bind_param("i",$listingid);
        $stmt->execute();
        $stmt->bind_result($listingDate,$bestByDate,$city,$county,$state);
        $stmt->fetch();
        echo "<li> Date Submitted: ".$listingDate." &nbsp;&nbsp;&nbsp;Best by Date: ".$bestByDate.
        " &nbsp;&nbsp;&nbsp;City: ".$city." &nbsp;&nbsp;&nbsp;County: ".$county." &nbsp;&nbsp;&nbsp;State: ".$state."</li>";
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

          echo "<tr>";
          while($stmt->fetch()){
            echo '<td>'.$produceName.'</td>';
            echo '<td>'.$produceType.'</td>';
            echo '<td>'.$measuremntValue.'</td>';
            echo '<td>'.$measurmentType.'</td>';
          }
          echo"</tr>";
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
