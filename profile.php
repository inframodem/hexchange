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
require_once 'config.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<link rel="stylesheet" href = "profile.css">
  <?php
  require_once 'navbar.php';
  $curruserId = "";
  if(isset($_SESSION["userId"])){
    $curruserId = $_SESSION["userId"];
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT userName FROM users WHERE idUsers = ?";
      $stmt->prepare($query);
      $stmt->bind_param("s",$curruserId);
      $stmt->execute();
      $stmt->bind_result($usertitle);
      $stmt->fetch();

      echo"<br><title> Profile: ".$usertitle."</title>";
    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
  }

  ?>


</head>
<body>
<div class="profileholder">
  <div class = "leftholder">
    <div class = "imageholder">
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
          echo "<li>Email: ".$email." &nbsp;&nbsp;Phone: ".$phonenumber." &nbsp;&nbsp; Fax: ".$Fax."</li>";
        }
        catch(mysqli_sql_exception $excep){
          echo "Database error:" . $conn->error;
          throw $excep;
        }
      }
      ?>
    </ul>
    </div>
    <div class = "SocialMedialist">
      <ul>
        <?php
        if(isset($_SESSION["userId"])){
          $curruserId = $_SESSION["userId"];
          try{
            $stmt = $conn->stmt_init();
            $query = "SELECT sm.socialMediaLink
            FROM socialmedia sm
            INNER JOIN contactsm csm ON csm.idSocialMedia = sm.idSocialMedia
            INNER JOIN contactinformation ci ON csm.idContactInformation = ci.idContactInformation
            INNER JOIN users u ON u.contactId = ci.idContactInformation WHERE u.idUsers = ?";
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
      <a href="profileform.php"> Edit Profile</a><br><br>
    </div>
  </div>
  <div class = "rightholder">
  <div class = "UserDescHolder">
    <?php
    echo"<br><h1>".$usertitle."'s Profile</h1>";
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
        echo "<p>".preg_replace('/\v+|\\\r\\\n/Ui','<br/>',$userdesc)."</p>";
      }
      catch(mysqli_sql_exception $excep){
        echo "Database error:" . $conn->error;
        throw $excep;
      }
    }
    ?>
  </div>
</div>

</div>
<div class = "UserProducelist">
  <a href="listingform.php"> Create New Listing</a><br>
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
            echo "<li><a href='listing.php?id=". $qlistId. "'>".$qlistTitle.'</a>'. "   Date Submitted: "
            . $qlistDate." &nbsp;&nbsp;&nbsp;Best by Date: ". $qBestByDate." &nbsp;&nbsp;&nbsp;City: ". $qCity." &nbsp;&nbsp;&nbsp;State: ".$qstate."
            <div class = 'deletebutton'><a href='deletelisting.php?deletebutton=". $qlistId. "'>   Delete</a></div></li>";

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
