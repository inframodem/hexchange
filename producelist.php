<?php
session_start();
require_once 'config.inc.php';
 ?>
<head>

</head>
<body>
  <?php

  require_once 'navbar.php';

    $states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');


   ?>
  <form method = "get" action=<?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
    Select State:<select id="statefilter" name="statefilter">
    <?php foreach ($states as $abr):
      echo('<option value = "'. $abr. '">'. $abr.'</option>');
        endforeach; ?>
     </select>
     City: <input type="text" id="cityfilter" name="cityfilter" maxlength="128">
     County: <input type="text" id="countyfilter" name="countyfilter" maxlength="128">
     <input type='submit' value="Submit">
  </form>
   <?php

    ?>
</body>
</html>
