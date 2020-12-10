
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
    <link rel="stylesheet" href = "userauth.css">
    <?php
		require_once 'navbar.php';


		?>
		<title></title>
	</head>
	<body>
    <div class="loginForm">
      <br><br>
      <form method="post" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
        <label for="username">Username: </label><input type="text" id="username" name="username" maxlength="32" required><br><br>
        <label for="password">Password: <input type="password" id="password" name="password" maxlength="128" required><br><br>
        <input type="submit" value = "Log in"><br>
      </form>
      <a href="createuser.php"> Create User</a><br>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if(!empty($_POST['username']) && !empty($_POST['password'])){
      //preg_match($userpattern, $username) && preg_match($passpattern, $password) &&
          $username = $conn->real_escape_string($_POST['username']);
          $password = $conn->real_escape_string($_POST['password']);
          $userpattern = "/^[a-zA-Z0-9!#]+$/";
          $passpattern = "/^[a-zA-Z0-9!#]+$ /";
          if((strlen($username) < 32 && strlen($password) < 128)){

            $passhash = password_hash($password, PASSWORD_DEFAULT);
            $query = "SELECT EXISTS(SELECT * FROM users WHERE userName = ?)";
            $stmt = $conn->stmt_init();
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("s",$username);
              $stmt->execute();
              $stmt->bind_result($userexists);
              $stmt->fetch();
              if($userexists){
                $query = "SELECT idUsers,passHash FROM users WHERE userName = ?";
                $stmt = $conn->stmt_init();
                if(!$stmt->prepare($query)){
                  echo "query failed";
                }
                else{
                  $stmt->bind_param("s",$username);
                  $stmt->execute();
                  $stmt->bind_result($quserId,$qpass);
                  $stmt->fetch();
                  if(password_verify($password,$qpass)){
                    $_SESSION['userId'] = $quserId;
                    $_SESSION['username'] = $username;
                    $conn->close();
                    sleep(3);
                    header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
                  }
                  else{
                    echo "<p>Incorrect User or Password</p>";
                  }

                }

          }
          else{
            echo "<p>Password or Username is Invalid</p>";
          }
       }

    }
    else{
      echo "<p>Password or Username is Invalid</p>";
    }
  }
  else{
    echo "<p>Password and Username are Required</p>";
  }
}
    $conn->close();
    ?>
</div>
	</body>
</html>
