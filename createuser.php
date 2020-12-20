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
    //basic elements including form elements
		?>
		<title>Create New User</title>
	</head>
	<body>
    <h1>Create New User Below</h1>
    <div class ="createUserForm">
    <form method="post" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      <label for="username">Username: </label><input type="text" id="username" name="username" maxlength="32" required><br><br>
      <label for="password">Password: </label> <input type="password" id="password" name="password" maxlength="128" required><br><br>
      <input type="submit" value = "Create User">
    </form>
    </div>
    <?php
    //only does after a post has occured
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if( !empty($_POST['username']) && !empty($_POST['password'])){
        //retreve username and password
          $username = $_POST['username'];
          $password = $_POST['password'];
          $userpattern = "/[a-zA-Z0-9!#]+/";
          $passpattern = "/[a-zA-Z0-9!#]+/";
          //makes sure both username name and password qualifies
          if(preg_match($userpattern, $username) && preg_match($passpattern, $password) &&
          (strlen($username) < 32 && strlen($password) < 128)){
            $passhash = password_hash($password, PASSWORD_DEFAULT);
            //query user for if user name already exists
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
              if(!$userexists){
                //create new user
                $query = "INSERT INTO users(idUsers, userName, passHash) VALUES(uuid(),?,?)";
                if(!$stmt->prepare($query)){
                  echo "query failed";
                }
                else{
                  $stmt->bind_param("ss",$username,$passhash);
                  $stmt->execute();
                  echo "User Created!";
                  //create username and uuid session variables
                  $query = "SELECT idUsers FROM users WHERE userName = ?";
                  $stmt->prepare($query);
                  $stmt->bind_param("s",$username);
                  $stmt->execute();
                  $stmt->bind_result($_SESSION['userId']);
                  $stmt->fetch();
                  $_SESSION["username"] = $username;
                  $conn->close();
                  sleep(3);
                  //redirect to profile page
                  echo "<script type='text/javascript'>
          	    window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/profile.php';
                    </script>";

                }

              }
              else{
                echo "User already exists!";
              }
            }
          }
          else{
            echo "Password or Username is Invalid";
          }
       }
       else{
         echo "Password and Username are Required";
       }
     }
       $conn->close();
    ?>
	</body>
</html>
