
<?php
session_start();
if(isset($_SESSION['LAST_ACTIVITY']) && (time()- $_SESSION['LAST_ACTIVITY'] > 3600)){

}
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
      <input type="submit" value = "Submit">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if( !empty($_POST['username']) && !empty($_POST['password'])){
          $username = $_POST['username'];
          $password = $_POST['password'];
          $userpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"\s]"
          $passpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"]"
          if(preg_match($userpattern, $username) && preg_match($passpattern, $password) &&
          (strlen($username) > 32 || strlen($password) > 128)){
          $passhash = password_hash($password, PASSWORD_DEFAULT);
          
          }
          else{
            echo "Password or Username is Invalid";
          }
       }
       else{
         echo "Password and Username are Required";
       }

    }
    ?>
	</body>
</html>
