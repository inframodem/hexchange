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
    <link rel="stylesheet" href="profileform.css">
    <?php
		require_once 'navbar.php';

		?>
		<title>Listing Form</title>
	</head>
	<body>
		<?php
//an array of states
    $states = array('','AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');
    $listingid;
    $mode;
    $genpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"\s]";
    //form for adding a listing
		?>
    <div class="formholder">
    <form method="post" id = "listingform" enctype = "multipart/form-data" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      <label for ="listingtitle">Title:</label><input type="text" id="listingtitle" name="listingtitle" maxlength="255" required><br>
      <label for ="bestbydate">Best by Date:</label><input type="date" id="bestbydate" name="bestbydate" format><br>
      <label for ="streetaddress1">Street Address 1:</label><input type="text" id="streetaddress1" name="streetaddress1" maxlength="255"><br>
      <label for ="streetaddress2">Street Address 2:</label><input type="text" id="streetaddress2" name="streetaddress2" maxlength="255"><br>
      <label for ="city">City:</label><input type="text" id="city" name="city" maxlength="255"><br>
      <label for ="county">County:</label><input type="text" id="county" name="county" maxlength="255"><br>
      <label for ="statefilter">Select State:</label><select id="statefilter" name="statefilter">
      <?php foreach ($states as $abr):
        echo('<option value = "'. $abr. '">'. $abr.'</option>');
          endforeach; ?>
       </select><br>
       <label for="listdesc">Listing Description: </label><br><textarea id="listdesc" name="listdesc" rows="8" cols="65" maxlength="1025"></textarea><br>
      <button type="button" id="addproduce" onclick="addProduceInput()"> Add Produce</button><br>
      <button type="button" id="removeproduce" onclick="removeProduceInput()">Remove Produce</button><br>
      <label for="listimage">Listing Image (8MB Size Limit): </label><input type="file" id="listimage" name="listimage" onchange="filevalidate()">
      <br><br>
      <input type="submit" id=listingsubmit value = "Submit">
    </form>
    <div>
<?php
//Java script for adding and removing produce elements
//also includes client side image validation checking
 ?>
    <script>
    var producecount = 0;
    var producearray = new Array();
    function addProduceInput(){
      var produceTypeArray = new Array("Fruit", "Vegetable", "Grain","Legume", "Other");
      var proform = document.getElementById('listingform');
      var addproduce = document.getElementById('addproduce');
//add produce name
      var produceName = document.createElement("input");
      produceName.setAttribute("type","text");
      produceName.setAttribute("id","produceName" + producecount);
      produceName.setAttribute("name","produceName"+ producecount);
      produceName.setAttribute("maxlength","255");
      produceName.required = true;
      //label
      var labelName = document.createElement("label");
      labelName.setAttribute("for","produceName" + producecount);
      labelName.innerHTML = "Produce Name: ";
      producearray.push(labelName);
      proform.insertBefore(labelName,addproduce);
      proform.insertBefore(produceName,addproduce);
      //newline
      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceName);
      producearray.push(nbreak);

//produce Type
//label
      var labelType = document.createElement("label");
      labelType.setAttribute("for","produceType" + producecount);
      labelType.innerHTML = "Produce Type: ";
      producearray.push(labelType);
      proform.insertBefore(labelType,addproduce);
      //select
      var produceType = document.createElement("select");
      produceType.setAttribute("name", "produceType"+ producecount);
      produceType.setAttribute("id", "produceType"+ producecount);
      proform.insertBefore(produceType, addproduce);
//options
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
//newline
      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceType);
      producearray.push(nbreak);

//produce measurement value
//label
      var labelValue = document.createElement("label");
      labelValue.setAttribute("for","producevalue" + producecount);
      labelValue.innerHTML = "Produce Measurement Amount: ";
      producearray.push(labelValue);
      //number
      var produceValue = document.createElement("input");
      produceValue.setAttribute("type","number");
      produceValue.setAttribute("id","producevalue" + producecount);
      produceValue.setAttribute("name","producevalue"+ producecount);
      produceValue.setAttribute("step","0.001");
      proform.insertBefore(labelValue,addproduce);
      proform.insertBefore(produceValue,addproduce);
      //newline
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceValue);
      producearray.push(nbreak);
//measurement unit
//label
      var labelUnit = document.createElement("label");
      labelUnit.setAttribute("for","produceUnit" + producecount);
      labelUnit.innerHTML = "Produce Measurement Unit: ";
      producearray.push(labelUnit);
      //text
      var produceUnit = document.createElement("input");
      produceUnit.setAttribute("type","text");
      produceUnit.setAttribute("id","produceUnit" + producecount);
      produceUnit.setAttribute("name","produceUnit"+ producecount);
      produceUnit.setAttribute("maxlength","255");
      proform.insertBefore(labelUnit,addproduce);
      proform.insertBefore(produceUnit,addproduce);
      //linebreak
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      producearray.push(produceUnit);
      producearray.push(nbreak);
      producecount++;
    }
    //removes elements added through addProduceInput
    function removeProduceInput(){
      if(producearray.length > 0){
      for(var i = 0; i <=5; i++){
        var rbreak = producearray.pop();
        rbreak.remove();
        rbreak = producearray.pop();
        rbreak.remove();
      }
      producecount--;
    }
    }
//varifies if the image is under the 8mb and if the file is the right image format
    function filevalidate() {
      var uploadField = document.getElementById("listimage");
        if(uploadField.files[0].size > 8388608){
           alert("File is too Big");
           uploadField.value = "";
        }
        var fileext = uploadField.files[0].name.split(".");
        var fileext = fileext[fileext.length - 1];
        var isExt;
        switch(fileext){
          case "png":
          case "jpg":
          case "bmp":
          case "gif":
          isExt = true;
          break;
          default:
          isExt = false;
          break;
        }
        if(!isExt){
          alert("File is not an image");
          uploadField.value = "";
        }
    }
    </script>
    <?php
//only work if session id is set and post has occured
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['userId'])) {
      $dateReg = "/\d\d\d\d-\d\d-\d\d/";
      $bestByDate = NULL;
      $city = NULL;
      $county = NULL;
      $state = NULL;
      $listdesc = NULL;
      $listtitle = NULL;
      $address1 = NULL;
      $address2= NULL;
      $filename = "Users/";
      $curruserId = $_SESSION['userId'];
//verify all variable used in the form
      if(!empty($_POST['bestbydate']) && strlen($_POST['city']) <= 255){
        $bestByDate =  $conn->real_escape_string($_POST['bestbydate']);
      }
      if(!empty($_POST['city']) && strlen($_POST['city']) <= 255){
        $city =  $conn->real_escape_string($_POST['city']);
      }
      if(!empty($_POST['county']) && strlen($_POST['county']) <= 255){
        $county =  $conn->real_escape_string($_POST['county']);
      }
      if(!empty($_POST['statefilter']) && strlen($_POST['statefilter']) <= 255){
        $state =  $conn->real_escape_string($_POST['statefilter']);
      }
      if(!empty($_POST['listdesc']) && strlen($_POST['listdesc']) <= 1025){
        $listdesc = $conn->real_escape_string($_POST['listdesc']);
      }
      if(!empty($_POST['listingtitle']) && strlen($_POST['listingtitle']) <= 255){
        $listtitle = $conn->real_escape_string($_POST['listingtitle']);
      }
      if(!empty($_POST['streetaddress1']) && strlen($_POST['streetaddress1']) <= 255){
        $address1 = $conn->real_escape_string($_POST['streetaddress1']);
      }
      if(!empty($_POST['streetaddress2']) && strlen($_POST['streetaddress2']) <= 255){
        $address2 = $conn->real_escape_string($_POST['streetaddress2']);
      }

// add all basic listing information to listing
        $stmt = $conn->stmt_init();
        $query =
        "INSERT INTO listing(listingDate, bestByDate, city, county, state, listingTitle, address1, address2)
        VALUES(CURRENT_DATE(), ?, ?, ?, ?, ?, ?, ?)";
        $stmt->prepare($query);
        $stmt->bind_param("sssssss",$bestByDate,$city,$county,$state,$listtitle,$address1,$address2);
        $stmt->execute();


        $stmt = $conn->stmt_init();
        $query = "SELECT last_insert_id() FROM listing";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->execute();
        $stmt->bind_result($currListing);
        $stmt->fetch();
//add listing id to junction listing user table
        $stmt = $conn->stmt_init();
        $query = "INSERT INTO listinguser(idUsers, idListing)
        VALUES (?, ?)";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param("si",$curruserId, $currListing);
        $stmt->execute();

        $stmt = $conn->stmt_init();
        $query = "UPDATE listing SET listingDesc = ? WHERE idListing = ?";
        $stmt = $conn->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param("si",$listdesc, $currListing);
        $stmt->execute();
        $conn->commit();

//add image to proper directory
        if(isset($_FILES['listimage']['name'])){
          $filename = 'Listings/'.$currListing;
          $ext = pathinfo($_FILES['listimage']['name'],PATHINFO_EXTENSION);
          $rightext = false;
          switch($ext){
            case "png":
            case "jpg":
            case "bmp":
            case "gif":
            $rightext = true;
            break;
            default:
            $rightext = false;
            break;
          }
          //make sure its the right size and format server side
          if($_FILES['listimage']['size'] < 8388608 && $rightext){
            if (!file_exists($filename)) {
              mkdir($filename, 0777, true);
            }
            //move image to directory
            $filename = $filename."/listingimage.".$ext;
            if(!file_exists($filename)){
              unlink($filename);
            }
            move_uploaded_file($_FILES['listimage']['tmp_name'], $filename);
            $stmt = $conn->stmt_init();
            $query = "UPDATE listing SET lsImage = ? WHERE idListing = ?";
            $stmt->prepare($query);
            $stmt->bind_param("si",$filename, $currListing);
            $stmt->execute();
          }
        }

       $produceArray = array(array(),
       array(),
       array(),
       array());
       $producecount = 0;

       $emp = NULL;
//push produce or null onto a 2d array with an array for each input for produce
       while(!Empty($_POST['produceName'. $producecount]) &&
       isset($_POST['produceName'. $producecount]) && $producecount < 20){
         array_push($produceArray[0],$conn->real_escape_string($_POST['produceName'.$producecount]));
         if(!empty($_POST['produceType'. $producecount])){
           echo "type";
           array_push($produceArray[1],$conn->real_escape_string($_POST['produceType'.$producecount]));
         }
         else{
           array_push($produceArray[1],$emp);
         }
         if(!empty($_POST['producevalue'.$producecount])){
           echo "value";
           array_push($produceArray[2],$_POST['producevalue'.$producecount]);
         }
         else{
           array_push($produceArray[2],$emp);
         }
         if(!empty($_POST['produceUnit'.$producecount])){
           echo "unit";
           array_push($produceArray[3],$conn->real_escape_string($_POST['produceUnit'.$producecount]));
         }
         else{
           array_push($produceArray[3],$emp);
         }
         $producecount++;
       }
//insert a column of the 2d array until name is null
       $producecount = 0;
       for($x = 0; $x < count($produceArray[0]); $x++) {
         $ProduceQName = "";
         $ProduceQType = "";
         $ProduceQValue = "";
         $ProduceQunit = "";

          $ProduceQName = $produceArray[0][$x];
          $ProduceQType = $produceArray[1][$x];
          $ProduceQValue = doubleval($produceArray[2][$x]);
          $ProduceQunit = $produceArray[3][$x];
          echo $ProduceQunit . $ProduceQType . $ProduceQValue."<br>";
//inserts produce into a produce table and connects everything to the produce junction table with listing
           $stmt = $conn->stmt_init();
           $query = "INSERT INTO produce(produceType, measurementType, measurementValue, produceName)
           VALUES(?,?,?,?)";
           $stmt->prepare($query);
           $stmt->bind_param("ssds",$ProduceQType,$ProduceQunit, $ProduceQValue, $ProduceQName);
           $stmt->execute();

           $stmt = $conn->stmt_init();
           $query = "SELECT last_insert_id() FROM produce";
           $stmt = $conn->stmt_init();
           $stmt->prepare($query);
           $stmt->execute();
           $stmt->bind_result($currProduce);
           $stmt->fetch();

           $stmt = $conn->stmt_init();
           $query = "INSERT INTO listingproduce(idListing, idProduce)
           VALUES( ?, ?)";
           $stmt->prepare($query);
           $stmt->bind_param("ii", $currListing,$currProduce);
           $stmt->execute();
       }
       //redirect to profile
         echo "<script type='text/javascript'>
         window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/profile.php';
         </script>";
    }
        $conn->close();
    ?>
	</body>
</html>
