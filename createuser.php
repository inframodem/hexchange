<?php
session_start();
require_once 'config.inc.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<?php
		require_once 'navbar.php';
		?>
    <form method="post" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      Username:<input type="text" id="username" name="username" maxlength="32"><br>
      Password: <input type="text" id="password" name="password" maxlength="128"><br>
      <input type="submit" value = "Create User">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(strlen($username) > 32 || strlen($password) > 128){
        echo("Your password or username is too long");
      }
      else{
        echo($password ." ". $username );
      }

    }
    ?>
	</body>
</html>
