<?php
  session_start();
  //unsets and destoy's sessions
  session_unset();
  session_destroy();
  //redirects to home
  echo "<script type='text/javascript'>
          window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/Index.php';
          </script>";

?>
