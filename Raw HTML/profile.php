<?php
session_start();
if(isset($_SESSION['LAST_ACTIVITY']) && (time()- $_SESSION['LAST_ACTIVITY'] > 7200)){
  session_unset();
  session_destroy();
}
else{
  $_SESSION['LAST_ACTIVITY'] = time();
}
if(isset($_SESSION["userId"])){

}
require_once 'config.inc.php';
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["userId"])){
    $delcount = 0;
    while(isset($_POST['del'.$delcount])){
      $listingnum = $_POST['del'.$delcount];
      $curruserId = $_SESSION["userId"];
      try{
        $stmt = $conn->stmt_init();
        $query = "DELETE listinguser,listing,listingproduce,produce FROM listinguser lu
        (((INNER JOIN listing l ON lu.idListing = l.idListing)
        INNER JOIN listingproduce lp ON l.idListing = lp.idListing)
        INNER JOIN produce p ON lp.idProduce = p.idProduce)
        WHERE lu.idUsers = ? ANDlu.idListing = ?";
        $stmt->prepare($query);
        $stmt->bind_param("si",$curruserId,intval($listingnum));
        $stmt->execute();
      }
        catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
      $delcount++;
    }

  }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <?php
  $curruserId = "";
  if(isset($_SESSION["userId"])){
    $curruserId = $_SESSION["userId"];
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT userName FROM users WHERE idUsers = ?";
      $stmt->prepare($query);
      $stmt->bind_param("i",$curruserId);
      $stmt->execute();
      $stmt->bind_result($usertitle);
      $stmt->fetch();
    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
  }
  require_once 'navbar.php';
  ?>

  <title><?php echo $usertitle;?></title>

</head>
<body>

  <div class = "UserDescHolder">
    <?php
    $curruserId = "";
    if(isset($_SESSION["userId"])){
      $curruserId = $_SESSION["userId"];
      try{
        $stmt = $conn->stmt_init();
        $query = "SELECT userDesc FROM users WHERE idUsers = ?";
        $stmt->prepare($query);
        $stmt->bind_param("s",$curruserId);
        $stmt->execute();
        $stmt->bind_result($userdesc);
        $stmt->fetch();
        echo "<p>".$userdesc."</p>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </div>
  <div class = "AccountContact">
    <ul>
    <?php
    $curruserId = "";
    if(isset($_SESSION["userId"])){
      $curruserId = $_SESSION["userId"];
      try{
        $stmt = $conn->stmt_init();
        $query = "SELECT ci.email,ci.phoneNumber,ci.FaxNumber FROM contactinformation ci
        INNER JOIN users u ON u.contactId = ci.idContactInformation
        WHERE idUsers = ?";
        $stmt->prepare($query);
        $stmt->bind_param("s",$curruserId);
        $stmt->execute();
        $stmt->bind_result($email,$phonenumber,$Fax);
        $stmt->fetch();
        echo "<li>".$email."   ".$phonenumber."   ".$Fax."</li>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </ul>
  </div>
  <div clas = "SocialMedialist">
    <ul>
      <?php
      if(isset($_SESSION["userId"])){
        $curruserId = $_SESSION["userId"];
        try{
          $stmt = $conn->stmt_init();
          $query = "SELECT sm.socialMediaLink
          FROM socialmedia sm INNER JOIN contactsm csm ON csm.idSocialMedia = csm.idSocialMedia
          INNER JOIN contactinformation ci ON csm.idContactInformation = ci.idContactInformation
          INNER JOIN users u ON u.contactId = ci.idContactInformation WHERE idUsers = ?";
          $stmt->prepare($query);
          $stmt->bind_param("s",$curruserId);
          $stmt->execute();
          $stmt->bind_result($socialMediaLink);
          while($stmt->fetch()){
            echo "<li>";
            echo '<a href="'.$socialMediaLink.'"> Social Media Link</a>';
            echo "</li>";
          }
        }
        catch(mysqli_sql_exception $excep){
          echo "Database error:" . $conn->error;
          throw $excep;
        }
      }
      ?>
    </ul>
  </div>
  <a href="profileform.php"> EditProfile</a><br><br>
  <a href="listingform.php"> Create New Listing</a><br>
  <div clas = "UserProducelist">
    <ul>
      <?php
      if(isset($_SESSION["userId"])){
        $curruserId = $_SESSION["userId"];
        $stmt = $conn->stmt_init();
        try{
          $query = "SELECT l.idListing, l.listingTitle, l.listingDate, l.bestByDate, l.city, l.state
          FROM listing l INNER JOIN listinguser ul ON ul.idListing = l.idListing
          WHERE ul.idUsers = ?
          ORDER BY l.listingDate";
          $stmt->prepare($query);
          $stmt->bind_param("s",$curruserId);
          $stmt->execute();
          $stmt->bind_result($qlistId,$qlistTitle,$qlistDate,$qBestByDate,$qCity,$qstate);
          echo "<ul>";
          $listcount = 0;
            while($stmt->fetch()){
              echo "<li><a href='listing.php?id=". $qlistId. "'>".$qlistTitle.'</a>'. "   "
              . $qlistDate. "   ". $qBestByDate."   ". $qdate."   ".$qstate;
              echo "<form action=".htmlspecialchars($_SERVER["PHP_SELF"])."method='post'>";
              echo '<button name="del'.$listcount.'" type="submit" value='.$qlistId.'>Delete</button>';
              echo"</form> </li>";
              $listcount++;
            }
          echo "</ul>";
          $listcount = 0;

        }
        catch(mysqli_sql_exception $excep){
          echo "Database error:" . $conn->error;
          throw $excep;
        }
        $conn->close();
      }
      ?>
    </ul>
  </div>

</body>
</html>
