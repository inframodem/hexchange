<link rel="stylesheet" href = "navbar.css">
<div class = "navbar">
  <a href="Index.php">Hunger Exchange</a>
  <div class="select-page">
    <a href="producelist.php">Listings</a>
    <a href="profile.php">My Profile</a>
    <a href="login.php">Log in</a>
    <a href="logout.php">Log out</a>
    <p>
    <?php
      if(isset($_SESSION['username'])){
        echo' Hello, '.$_SESSION['username'];
      }
     ?>
   </p>
  </div>

</div>
