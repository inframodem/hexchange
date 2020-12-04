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
  <?php
  $listingid = "";
  if(isset($_GET["id"])){
    $listingid = $_GET["id"];
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT listingTitle FROM listing WHERE idListing = ?";
      $stmt->prepare($query);
      $stmt->bind_param("i",intval($listing));
      $stmt->execute();
      $stmt->bind_result($listtitle);
      $stmt->fetch();
    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
  }
  require_once 'navbar.php';
  ?>

  <title><?php echo $listtitle;?></title>

</head>
<body>

  <div class = "DescHolder">
    <?php
    $listingid = "";
    if(isset($_GET["id"])){
      $listingid = $_GET["id"];
      try{
        $stmt = $conn->stmt_init();
        $query = "SELECT listingDesc FROM listing WHERE idListing = ?";
        $stmt->prepare($query);
        $stmt->bind_param("i",intval($listing));
        $stmt->execute();
        $stmt->bind_result($listdesc);
        $stmt->fetch();
        echo "<p>".$listdesc."</p>";
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
        $stmt->bind_param("i",intval($listing));
        $stmt->execute();
        $stmt->bind_result($listingDate,$bestByDate,$city,$county,$state);
        $stmt->fetch();
        echo "<li>".$listingDate."   ".$bestByDate."   ".$city."   ".$county."   ".$state"</li>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </ul>
  </div>
  <div clas = "Produce">
    <table style ="width:100%">
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
          $query = "SELECT p.produceName,p.produceType,p.measuremntValue,p.measurmentType
          FROM produce p((INNER JOIN listingproduce lp ON p.idProduce = lp.idProduce)
          INNER JOIN listing l ON lp.idListing = l.idListing) WHERE idListing = ?";
          $stmt->prepare($query);
          $stmt->bind_param("i",intval($listing));
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
