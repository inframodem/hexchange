<?php
session_start();
?>
<html>
<body>
<?php
//sunset and remove session
if(isset($_SESSION['LAST_ACTIVITY']) && (time()- $_SESSION['LAST_ACTIVITY'] > 7200)){
  session_unset();
  session_destroy();
}
else{
  $_SESSION['LAST_ACTIVITY'] = time();
}
//checks if session is set
if(!isset($_SESSION["userId"])){
  echo "<script type='text/javascript'>
  window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/profile.php';
  </script>";

}

require_once "config.inc.php";
echo $conn->real_escape_string($_SESSION["userId"]);
if(isset($_SESSION["userId"]) && isset($_GET['deletebutton'])){
//deletes all information regarding a listing from a db
    $listingnum = intval($_GET['deletebutton']);
    $curruserId = $_SESSION["userId"];
    $stmt = $conn->stmt_init();
    $query = "DELETE lu,l,lp,p FROM listinguser lu
    LEFT JOIN listing l ON lu.idListing = l.idListing
    LEFT JOIN listingproduce lp ON l.idListing = lp.idListing
    LEFT JOIN produce p ON lp.idProduce = p.idProduce
    WHERE lu.idUsers = ? AND lu.idListing = ?";
    if(!$stmt->prepare($query)){
      echo "query fail delete";
    }
    else{
      $stmt->bind_param("si",$curruserId,$listingnum);
      $stmt->execute();
    }
  }
  //redirects to profile
    $conn->close();
    echo "<script type='text/javascript'>
  window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/profile.php';
  </script>";

  ?>
</body>
</html>
