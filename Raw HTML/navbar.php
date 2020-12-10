<div class = "navbar">
  <a href="index.php">Home</a>
  <a href="producelist.php">Listings</a>
  <a href="profile.php">My Profile</a>
  <a href="login.php">Log in</a>
  <a href="logout.php">Log Out</a>
  <?php
    if(isset($_SESSION['username'])){
      echo' Hello, '.$_SESSION['username'];

    }
   ?>
</div>
