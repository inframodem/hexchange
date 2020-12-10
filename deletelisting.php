<html>
<body>
<?php
session_start();
if(isset($_SESSION['LAST_ACTIVITY']) && (time()- $_SESSION['LAST_ACTIVITY'] > 7200)){
  session_unset();
  session_destroy();
}
else{
  $_SESSION['LAST_ACTIVITY'] = time();
}
if(!isset($_SESSION["userId"])){
  header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
}

require_once "config.inc.php";
echo $conn->real_escape_string($_SESSION["userId"]);
if(isset($_SESSION["userId"]) && isset($_GET['deletebutton'])){

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
    $conn->close();
    header("Location: http://".$_SERVER['HTTP_HOST']."/profile.php");

  ?>
</body>
</html>
