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
    $states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC',
  'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MA', 'MI',
  'MN', 'MS', 'MO', 'MT', 'NM', 'NY', 'NC', 'ND', 'OH','OK', 'OR', 'PA',
  'RI', 'SC', 'SD', 'TN', 'TX', 'UT','VT','VA','WA','WV','WI', 'WY', 'PR');
    $listingid;


		?>
    <form method="post" id = "listingform" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      Best by Date:<input type="date" id="bestbydate" name="bestbydate">
      City:<input type="text" id="city" name="city" maxlength="128"><br>
      County:<input type="text" id="county" name="county" maxlength="128"><br>
      Select State:<select id="statefilter" name="statefilter">
      <?php foreach ($states as $abr):
        echo('<option value = "'. $abr. '">'. $abr.'</option>');
          endforeach; ?>
       </select>
      <button type="button" id="addproduce" onclick="addProduce()"> Add Produce</button><br>
      <button type="button" id="removeproduce" onclick="removeProduce()">Remove Produce</button><br>
      <input type="submit" id=listingsubmit value = "Submit">
    </form>

    <script>
    function addProduce(){
      var producecount = 0;
      var producearray = new Array();
      var produceTypeArray = new Array("Fruit", "Vegetable", "Grain","Legume", "Other");
      var proform = document.getElementById('listingform');
      var addproduce = document.getElementById('addproduce');

      var produceName = document.createElement("input");
      produceName.setAttribute("type","text");
      produceName.setAttribute("id","produceName" + producecount);
      produceName.setAttribute("name","produceName"+ producecount);
      var labelName = document.createElement("label");
      labelSocial.setAttribute("for","produceName" + producecount);
      labelSocial.innerHTML = "Produce Name: ";
      socialarray.push(labelName);
      proform.insertBefore(produceName,addproduce);
      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      socialarray.push(produceName);
      socialarray.push(nbreak);
      producecount++;

      var labelName = document.createElement("label");
      labelSocial.setAttribute("for","produceType" + producecount);
      labelSocial.innerHTML = "Produce Type: ";
      socialarray.push(labelType);
      var produceType = document.createElement("select");
      produceType.setAttribute("name", "produceType"+ producecount);
      produceType.setAttribute("id", "produceType"+ producecount);
      proform.insertBefore(produceType, addproduce);
      for(var i = 0; i < produceType.length; i++){
        var newoption = document.createElement("option");
        newoption.setAttribute("value", produceTypeArray[i]);
        newoption.innerHTML = produceTypeArray[i];
        produceType.appendChild(newoption);
      }
      var nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      socialarray.push(produceType);
      socialarray.push(nbreak);
      producecount++;

      var labelValue = document.createElement("label");
      labelSocial.setAttribute("for","producevalue" + producecount);
      labelSocial.innerHTML = "Produce Measurement Amount: ";
      socialarray.push(labelValue);
      var produceValue = document.createElement("input");
      produceName.setAttribute("type","number");
      produceName.setAttribute("id","producevalue" + producecount);
      produceName.setAttribute("name","producevalue"+ producecount);
      produceName.setAttribute("step","0.001");
      proform.insertBefore(produceValue,addproduce);
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      socialarray.push(produceValue);
      socialarray.push(nbreak);
      producecount++;

      var labelValue = document.createElement("label");
      labelSocial.setAttribute("for","produceUnit" + producecount);
      labelSocial.innerHTML = "Produce Measurement Unit: ";
      socialarray.push(labelUnit);
      var produceUnit = document.createElement("input");
      produceName.setAttribute("type","text");
      produceName.setAttribute("id","produceUnit" + producecount);
      produceName.setAttribute("name","produceUnit"+ producecount);
      proform.insertBefore(produceUnit,produceUnit);
      nbreak = document.createElement("br");
      proform.insertBefore(nbreak, addproduce);
      socialarray.push(produceName);
      socialarray.push(nbreak);
      producecount++;
    }
    function removeProduce(){
      for(var i = 0; i <=4; i++){
        var rbreak = producearray.pop();
        rbreak.remove();
        rbreak = producearray.pop();
        rbreak.remove();
        socialcount--;
      }
    }
    </script>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if( !empty($_POST['produceName']) && !empty($_POST['produceType']) &&
        !empty($_POST['producevalue']) && !empty($_POST['produceUnit'])){
          $produceName = $_POST['produceName'];
          $produceType = $_POST['produceType'];
          $produceValue = $_POST['producevalue'];
          $produceUnit = $_POST['produceUnit'];

          $userpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\" ]"
          $passpattern = "[a-zA-Z0-9!#\$%\&'\(\)\*\+,-\./:;<=>\?@\[\\\]\^_`{\|}~\"]"
          if(preg_match($userpattern, $username) && preg_match($passpattern, $password) ){
          if(strlen($username) > 32 || strlen($password) > 128){
              echo("Your password or username is too long");
            }
            else{
              echo($password ." ". $username );
            }
          }
          else{
            echo "Password or Username contains invalid characters";
          }
       }
       else{
         echo "Password and Username are Required";
       }

    }
    ?>
	</body>
</html>
