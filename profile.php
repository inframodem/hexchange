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
  echo "<script type='text/javascript'>
  window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/login.php';
  </script>";

}
require_once 'config.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<link rel="stylesheet" href = "profile.css">
  <?php
  //gets username and type for page title
  require_once 'navbar.php';
  $curruserId = "";
  if(isset($_SESSION["userId"])){
    $curruserId = $_SESSION["userId"];
    try{
      $stmt = $conn->stmt_init();
      $query = "SELECT userName, userType FROM users WHERE idUsers = ?";
      $stmt->prepare($query);
      $stmt->bind_param("s",$curruserId);
      $stmt->execute();
      $stmt->bind_result($usertitle,$usertype);
      $stmt->fetch();
	$usertypename = "Producer";
       if($usertype == 1){
	$usertypename = "Food Bank";
	}
      echo"<br><title> Profile: ".$usertitle."</title><br>";
    }
    catch(mysqli_sql_exception $excep){
      echo "Database error:" . $conn->error;
      throw $excep;
    }
  }

  ?>


</head>
<body>
<div class="page">
<div class="profileholder">
  <div class = "leftholder">
    <div class = "imageholder">
      <?php
      //show image for profile from path in db
      if(isset($_SESSION["userId"])){
        $stmt = $conn->stmt_init();
        $query = "SELECT pImage
        FROM users
        WHERE idUsers = ?";
        $stmt->prepare($query);
        $stmt->bind_param("s",$curruserId);
        $stmt->execute();
        $stmt->bind_result($pimage);
        $stmt->fetch();
        echo "<img src=".$pimage.">";
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
          //get contact information
          $stmt = $conn->stmt_init();
          $query = "SELECT ci.email,ci.phoneNumber,ci.FaxNumber FROM contactinformation ci
          INNER JOIN users u ON u.contactId = ci.idContactInformation
          WHERE idUsers = ?";
          $stmt->prepare($query);
          $stmt->bind_param("s",$curruserId);
          $stmt->execute();
          $stmt->bind_result($email,$phonenumber,$Fax);
          $stmt->fetch();
          echo "<li>Email: ".$email." <br> Phone: ".$phonenumber." <br> Fax: ".$Fax."</li>";
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
            //get social media links and names
            $stmt = $conn->stmt_init();
            $query = "SELECT sm.socialMediaLink,sm.socialMediaName
            FROM socialmedia sm
            INNER JOIN contactsm csm ON csm.idSocialMedia = sm.idSocialMedia
            INNER JOIN contactinformation ci ON csm.idContactInformation = ci.idContactInformation
            INNER JOIN users u ON u.contactId = ci.idContactInformation WHERE u.idUsers = ?";
            $stmt->prepare($query);
            $stmt->bind_param("s",$curruserId);
            $stmt->execute();
            $stmt->bind_result($socialMediaLink, $socialMediaName);
            //add all links to a list
            while($stmt->fetch()){
              echo "<li>";
              echo '<a href="'.$socialMediaLink.'"> Social Media Link: '.$socialMediaName.'</a>';
              echo "</li>";
            }
          }
          catch(mysqli_sql_exception $excep){
            echo "Database error:" . $conn->error;
            throw $excep;
          }
        }
        //profile edit button to profile form
        ?>

      </ul>
      <a href="profileform.php"> Edit Profile</a><br><br>
    </div>
  </div>
  <div class = "rightholder">
  <div class = "UserDescHolder">
    <?php
    //get profile description
    echo"<br><h1>".$usertitle."'s Profile<br>". $usertypename. "</h1>";
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
        //add newlines and remove slashes
	$descbetter = preg_replace('/\v+|\\\r\\\n/Ui','<br/>',$userdesc);
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
</div>

</div>
<div class = "UserProducelist">

  <ul>
    <a href="listingform.php"> Create New Listing</a><br>
    <?php
    if(isset($_SESSION["userId"])){
      $curruserId = $_SESSION["userId"];
      $stmt = $conn->stmt_init();
      //get basic listing information
      try{
        $query = "SELECT l.idListing, l.listingTitle, l.listingDate, l.bestByDate, l.city, l.state
        FROM listing l INNER JOIN listinguser ul ON ul.idListing = l.idListing
        WHERE ul.idUsers = ?
        ORDER BY l.listingDate DESC";
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
</div>
</body>
</html>
