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
		<?php
		require_once 'navbar.php';
		$stmt = $conn->stmt_init();
		$query = "SELECT email,phoneNumber,FaxNumber FROM contactinformation WHERE idContactInformation = ?";
		$lastins = 24;
			$stmt->prepare($query);
			$stmt->bind_param("i",$lastins);
			$stmt->execute();
			$stmt->bind_result($nemail, $nphonenumber,$nfaxnumber);
			$stmt->fetch();
			echo "<br> ".$nemail.$nphonenumber.$nfaxnumber;

		?>
		<title></title>
	</head>
	<body>


	</body>
</html>
