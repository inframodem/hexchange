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
		<link rel="stylesheet" href = "homepage.css">
		<meta charset="utf-8">
		<?php
		require_once 'navbar.php';

		?>
		<title>Home Page</title>
	</head>
	<body>
		<div class="homepageholder">
			<!--Credit to Julia Volk for the free stock image
		https://www.pexels.com/photo/different-vegetables-in-pile-at-street-market-5273080/-->
		<div class = "backdrop">
			<div class = "homecontent">
				<h2>Welcome to Hunger Exchange</h2>
				<p> This is a website dedicated to helping form connections between Food
					Banks and local farms to help feed those impoverished. <br> Make and Account and a new listing
				 today! </p>
			</div>
		</div>

		</div>

	</body>
</html>
