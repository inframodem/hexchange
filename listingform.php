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


		?>
		<title></title>
	</head>
	<body>
		<?php

    $states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');
    $listingid;
    $mode;
    $genpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"\s]";

		?>
    <form method="post" id = "listingform" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      Title:<input type="text" id="listingtitle" name="listingtitle" maxlength="255"><br>
      Best by Date:<input type="date" id="bestbydate" name="bestbydate" format>
      City:<input type="text" id="city" name="city" maxlength="255"><br>
      County:<input type="text" id="county" name="county" maxlength="255"><br>
      Select State:<select id="statefilter" name="statefilter">
      <?php foreach ($states as $abr):
        echo('<option value = "'. $abr. '">'. $abr.'</option>');
          endforeach; ?>
       </select><br>
       Listing Description: <br><textarea id="listdesc" name="listdesc" rows="8" cols="75" maxlength="1025"></textarea><br>
      <button type="button" id="addproduce" onclick="addProduceInput()"> Add Produce</button><br>
      <button type="button" id="removeproduce" onclick="removeProduceInput()">Remove Produce</button><br>
      <input type="submit" id=listingsubmit value = "Submit">
    </form>

    <script>
    var producecount = 0;
    var producearray = new Array();
    function addProduceInput(){
      var produceTypeArray = new Array("Fruit", "Vegetable", "Grain","Legume", "Other");
      var proform = document.getElementById('listingform');
      var addproduce = document.getElementById('addproduce');

      var produceName = document.createElement("input");
      produceName.setAttribute("type","text");
      produceName.setAttribute("id","produceName" + producecount);
      produceName.setAttribute("name","produceName"+ producecount);
      produceName.setAttribute("maxlength","255");
      var labelName = document.createElement("label");
      labelName.setAttribute("for","produceName" + producecount);
      labelName.innerHTML = "Produce Name: ";
      producearray.push(labelName);
      proform.insertBefore(labelName,addproduce);
      proform.insertBefore(produceName,addproduce);
      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceName);
      producearray.push(nbreak);
      producecount++;

      var labelType = document.createElement("label");
      labelType.setAttribute("for","produceType" + producecount);
      labelType.innerHTML = "Produce Type: ";
      producearray.push(labelType);
      proform.insertBefore(labelType,addproduce);
      var produceType = document.createElement("select");
      produceType.setAttribute("name", "produceType"+ producecount);
      produceType.setAttribute("id", "produceType"+ producecount);
      proform.insertBefore(produceType, addproduce);

      var newoption = document.createElement("option");
      newoption.value = produceTypeArray[0];
      newoption.text = produceTypeArray[0];
      produceType.add(newoption);
      var newoption1 = document.createElement("option");
      newoption1.value = produceTypeArray[1];
      newoption1.text = produceTypeArray[1];
      produceType.add(newoption1);
      var newoption2 = document.createElement("option");
      newoption2.value = produceTypeArray[2];
      newoption2.text = produceTypeArray[2];
      produceType.add(newoption2);
      var newoption3 = document.createElement("option");
      newoption3.value = produceTypeArray[3];
      newoption3.text = produceTypeArray[3];
      produceType.add(newoption3);
      var newoption4 = document.createElement("option");
      newoption4.value = produceTypeArray[4];
      newoption4.text = produceTypeArray[4];
      produceType.add(newoption4);

      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceType);
      producearray.push(nbreak);
      producecount++;

      var labelValue = document.createElement("label");
      labelValue.setAttribute("for","producevalue" + producecount);
      labelValue.innerHTML = "Produce Measurement Amount: ";
      producearray.push(labelValue);
      var produceValue = document.createElement("input");
      produceValue.setAttribute("type","number");
      produceValue.setAttribute("id","producevalue" + producecount);
      produceValue.setAttribute("name","producevalue"+ producecount);
      produceValue.setAttribute("step","0.001");
      proform.insertBefore(labelValue,addproduce);
      proform.insertBefore(produceValue,addproduce);
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceValue);
      producearray.push(nbreak);
      producecount++;

      var labelUnit = document.createElement("label");
      labelUnit.setAttribute("for","produceUnit" + producecount);
      labelUnit.innerHTML = "Produce Measurement Unit: ";
      producearray.push(labelUnit);
      var produceUnit = document.createElement("input");
      produceUnit.setAttribute("type","text");
      produceUnit.setAttribute("id","produceUnit" + producecount);
      produceUnit.setAttribute("name","produceUnit"+ producecount);
      produceUnit.setAttribute("maxlength","255");
      proform.insertBefore(labelUnit,addproduce);
      proform.insertBefore(produceUnit,addproduce);
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceUnit);
      producearray.push(nbreak);
      producecount++;
    }
    function removeProduceInput(){
      if(producearray.length > 0){
      for(var i = 0; i <=5; i++){
        var rbreak = producearray.pop();
        rbreak.remove();
        rbreak = producearray.pop();
        rbreak.remove();
        producecount--;
      }
    }
    }
    </script>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $dateReg = "\d\d\d\d-\d\d-\d\d";
      $bestByDate = NULL;
      $city = NULL;
      $county = NULL;
      $state = NULL;
      $listdesc = NULL;
      $listtitle = NULL;
      $filename = "Users/";

      if(!empty($_POST['bestbydate']) && preg_match($dateReg, $_POST['bestbydate']) &&
    strlen($_POST['city']) <= 255){
        $bestByDate =  $conn->real_escape_string($_POST['bestbydate']);
      }
      if(!empty($_POST['city']) && preg_match($genpattern, $_POST['city']) &&
      strlen($_POST['city']) <= 255){
        $city =  $conn->real_escape_string($_POST['city']);
      }
      if(!empty($_POST['county']) && preg_match($genpattern, $_POST['county']) &&
      strlen($_POST['county']) <= 255){
        $county =  $conn->real_escape_string($_POST['county']);
      }
      if(!empty($_POST['statefilter']) && preg_match($genpattern, $_POST['statefilter']) &&
      strlen($_POST['statefilter']) <= 255){
        $state =  $conn->real_escape_string($_POST['statefilter']);
      }
      if(!empty($_POST['listdesc']) && preg_match($generalReg,$_POST['listdesc']) &&
      strlen($_POST['listdesc']) <= 1025){
        $listdesc = $conn->real_escape_string($_POST['listdesc']);
      }
      if(!empty($_POST['listingtitle']) && preg_match($generalReg,$_POST['listingtitle']) &&
      strlen($_POST['listingtitle']) <= 255){
        $listtitle = $conn->real_escape_string($_POST['listingtitle']);
      }

      $conn->begin_transaction();
      try{
        $stmt = $conn->stm_int();
        $query =
        "INSERT INTO listing(listingDate, bestByDate, city, county, state, listingTitle)
        VALUES(CURRENT_DATE(), ?, ?, ?, ?)";
        $stmt->prepare($query);
        $stmt->bind_param("ssss",$bestByDate,$city,$county,$state,$listtitle);
        $stmt->execute();

        $query = "INSERT INTO listinguser(idUsers, listingId)
        SELECT ?, last_insert_id()
        FROM listing";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param("s",$_SESSION["userId"]);
        $stmt->execute();

        $query = "SELECT last_insert_id() FROM listing";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->execute();
        $stmt->bind_result($currListing);
        $stmt->fetch();

        $query = "UPDATE listing SET listingDesc = ? WHERE listingId = ?";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param("si",$listdesc, $listingid);
        $stmt->execute();
        $conn->commit();

      }
      catch(mysqli_sql_exception $excep){
        $conn->rollback();
        echo "Database error:" . $conn->error;
        throw $excep;
      }



       $produceArray = array(array(),
       array(),
       array(),
       array());
       $emp = NULL;
       $producecount = 0;
       while(!Empty($_POST['produceName'+ $producecount]) &&
       isset($_POST['produceName'+ $producecount]) && $producecount < 20){
         $produceArray[0].array_push($_POST['produceName'+$producecount]);
         if(!empty($_POST['produceType'+ $producecount])){
           $produceArray[1].array_push($_POST['produceType'+$producecount]);
         }
         else{
           $produceArray[1].array_push($emp);
         }
         if(!empty($_POST['producevalue'+$producecount])){
           $produceArray[2].array_push($_POST['producevalue'+$producecount]);
         }
         else{
           $produceArray[2].array_push($emp);
         }
         if(!empty($_POST['produceUnit'+$producecount])){
           $produceArray[3].array_push($_POST['produceUnit'+$producecount]);
         }
         else{
           $produceArray[3].array_push($emp);
         }
         $producecount++;
       }

       $producecount = 0;
       for($x = 0; $x < count($produceArray[0]); $x++) {
         $ProduceQ = array();
         for($y = 0; $y < count($produceArray); $y++){
           if(is_numeric($produceArray[$x][$y]) || (is_string($produceArray[$x][$y])) &&
            preg_match($genpattern, $produceArray[$x][$y])){
              $produceQ.array_push($produceArray[$x][$y]);
          }
         }
         $conn->begin_transaction();
         try{
           $stmt = $conn->stmt_init();
           $query = "INSERT INTO produce(produceType, measuremntType, measurementValue, produceName)
           VALUES(?,?,?,?)";
           $stmt->prepare($query);
           $stmt->bind_param("ssds", $produceQ[1], $produceQ[3], $produceQ[2], $produceQ[0]);
           $stmt->execute();

           $stmt = $conn->stmt_init();
           $query = "INSERT INTO listingproduce(idListing, idProduce)
           SELECT ?, last_insert_id()
           FROM produce";
           $stmt->prepare($query);
           $stmt->bind_param("i", $listingid);
           $stmt->execute();
           $conn->commit();
         }catch(mysqli_sql_exception $excep){
           $conn->rollback();
           echo "Database error:" . $conn->error;
           throw $excep;
         }

       }
       $conn->close();
    }
    ?>
	</body>
</html>
