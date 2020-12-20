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

    //profile form
		?>
		<title>Profile Form</title>
	</head>
	<body>
    <div class="formholder">
    <form method="post" id = "profileform" enctype = "multipart/form-data" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      <label for='usertype'>Choose a User Type:</label>
    	<select name='usertype' id='usertype'>
    	<option value='0'>Producer</option>
    	<option value='1'>Food Bank</option>
    	</select><br>
      <label for="emailedit">Email: </label><input type="text" id="emailedit" name="emailedit" maxlength="128"><br>
      <label for="phoneedit">Phone Number: </label><input type="tel" id="phoneedit" name="phoneedit" placeholder="000-000-0000"
      pattern="[\d]{3}-[\d]{3}-[\d]{4}"><br>
      <label for="faxedit">Fax Number: </label><input type="tel" id="faxedit" name="faxedit" placeholder="000-000-0000"
      pattern="[\d]{3}-[\d]{3}-[\d]{4}"><br>
      <button type="button" id="addsocial" onclick="addsociallink()"> Add Social Media</button><br>
      <button type="button" id="removesocial" onclick="removesociallink()">Remove Social Media</button><br>
      <textarea id="profiledesc" name="profiledesc" rows="8" cols="65" maxlength="1025"></textarea>
      <br><br>
      <label for="profileimage">Profile Image (8MB Size Limit): </label><input type="file" id="profileimage" name="profileimage" onchange="filevalidate()">
      <br><br>
      <input type="submit" value = "Submit">
    </form>
    <?php
      $phoneReg = "/^[\d]{3}-[\d]{3}-[\d]{4}$/";
      $emailReg = "/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z0-9]+$/";
      $generalReg = "/^[a-zA-Z0-9!#?!+\/\"'.$\_+\-&*\s=%#@()\[\]\{\}|\<\>\`\~;\^\\]+$/";

      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['userId'])) {
        $email = "";
        $phonenumber = "";
        $faxnumber = "";
        $desc= "";
	$usertype = 0;
  //verify all inputs
        $curruserId = $_SESSION["userId"];
        if(!empty($_POST['emailedit']) && preg_match($emailReg,$_POST['emailedit'])){
          $email = $conn->real_escape_string($_POST['emailedit']);
        }
        if(!empty($_POST['phoneedit']) && preg_match($phoneReg,$_POST['phoneedit'])){
          $phonenumber = $conn->real_escape_string($_POST['phoneedit']);
        }
        if(!empty($_POST['faxedit']) && preg_match($phoneReg,$_POST['faxedit'])){
          $faxnumber = $conn->real_escape_string($_POST['faxedit']);
        }
        if(!empty($_POST['profiledesc'])  && strlen($_POST['profiledesc']) <= 1025){
          $desc = $conn->real_escape_string($_POST['profiledesc']);
        }
	       if(!empty($_POST['usertype'])  && is_numeric($_POST['usertype'])){

          $usertype = intval($_POST['usertype']);

        }

        //verify profile image based on 8mb file size and file format
        if(isset($_FILES['profileimage']['name'])){
          $filename = 'Users/'.$curruserId;
          $ext = pathinfo($_FILES['profileimage']['name'],PATHINFO_EXTENSION);
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
          if($_FILES['profileimage']['size'] < 8388608 && $rightext){
            //move file to proper directory and add path to db
            if (!file_exists($filename)) {
              mkdir($filename, 0777, true);
            }

            $filename = $filename."/profileimage.".$ext;
            if(!file_exists($filename)){
              unlink($filename);
            }
            move_uploaded_file($_FILES['profileimage']['tmp_name'], $filename);
            $stmt = $conn->stmt_init();
            $query = "UPDATE users SET pImage = ? WHERE idUsers = ?";
            $stmt->prepare($query);
            $stmt->bind_param("ss",$filename, $curruserId);
            $stmt->execute();
          }
        }
//check if contact id is null
          $usercontact;
          $stmt = $conn->stmt_init();
          $query = "SELECT contactId FROM users WHERE idUsers = ?";
          $stmt->prepare($query);
          $stmt->bind_param("s",$curruserId);
          $stmt->execute();
          $stmt->bind_result($contactexists);
          $stmt->fetch();

          if(!is_null($contactexists)){
            // update contacts to contact information in which the id is added to the user table
            $stmt = $conn->stmt_init();
            $query = "UPDATE contactinformation ci
            INNER JOIN users u ON ci.idContactInformation = u.contactId
            SET email = ?, phoneNumber = ?,FaxNumber = ?
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
                echo "query failed";
            }
            else{
              $stmt->bind_param("ssss",$email,$phonenumber,$faxnumber,$curruserId);
              $stmt->execute();
            }
            $stmt = $conn->stmt_init();
            $query = "UPDATE users u
            SET userDesc = ?, userType = ?
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("sis",$desc,$usertype,$curruserId);
              $stmt->execute();
            }
//delete all social media
            $stmt = $conn->stmt_init();
            $query = "DELETE sm FROM socialmedia sm
            INNER JOIN contactsm csm ON csm.idSocialMedia= sm.idSocialMedia
            INNER JOIN contactinformation ci ON ci.idContactInformation = csm.idContactInformation
            INNER JOIN users u ON ci.idContactInformation = u.contactId
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
              echo $conn->error;
            }
            else{
              $stmt->bind_param("s",$curruserId);
              $stmt->execute();
              echo $conn->error;
            }

          }
          //if contact information does not exist
          else{
            $stmt = $conn->stmt_init();
            //insert inputs into a new contactinformation row
            $query = "INSERT INTO contactinformation(email,phoneNumber,FaxNumber) VALUES(?,?,?)";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("sss",$email,$phonenumber,$faxnumber);
              $stmt->execute();
            }
            $stmt = $conn->stmt_init();
            //add that row id to user
            $query = "SELECT last_insert_id() FROM contactinformation";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->execute();
              $stmt->bind_result($lastres);
              $stmt->fetch();
            }

            $query = "UPDATE users
            SET contactId = ?
            WHERE idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("is",$lastres, $curruserId);
              $stmt->execute();
            }
            $stmt = $conn->stmt_init();
            $query = "UPDATE users u
            SET userDesc = ?, userType = ?
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("sis",$profiledesc,$usertype,$curruserId);
              $stmt->execute();
            }
          }
          $socialArray = array();
          $soccount = 0;
          //add social media to array with link before name
          while(isset($_POST['social' . $soccount])){
             array_push($socialArray,$_POST['social' . $soccount]);
             array_push($socialArray,$_POST['socialname' . $soccount]);
             $soccount++;
          }
          $soccount = 0;
          for($i = 0;$i < count($socialArray);$i+=2){
            // add social medias to db
            $stmt = $conn->stmt_init();
            $query = "INSERT INTO socialmedia(socialMediaLink, socialMediaName)
            VALUES (?,?)";
            $stmt->prepare($query);
            $socinsert = $socialArray[$i];
            $socname = $socialArray[$i+1];
            $stmt->bind_param('ss',$socinsert,$socname);
            $stmt->execute();

            $stmt = $conn->stmt_init();
            $query = "SELECT last_insert_id() FROM socialmedia";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->execute();
              $stmt->bind_result($lastsmres);
              $stmt->fetch();
            }
            //add socialmedia id to junction table contact information social media
            $stmt = $conn->stmt_init();
            $query = "INSERT INTO contactsm(idContactInformation, idSocialMedia)
            SELECT u.contactId, ? FROM users u WHERE idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param('ss', $lastsmres,$curruserId);
              $stmt->execute();
            }
          }
          $conn->close();
          echo "<script type='text/javascript'>
          window.location.href = 'http://".$_SERVER['HTTP_HOST']."/alexp15/profile.php';
          </script>";


      }

//javascript for profile form to add social media input and verify images
    ?>
  </div>
  <script>
  var socialcount = 0;
  var socialarray = new Array();
  function addsociallink(){
    var proform = document.getElementById('profileform');
    var profsubmit = document.getElementById('addsocial');
    //social media link text
    var profileEditForm = document.createElement("input");
    profileEditForm.setAttribute("type","text");
    profileEditForm.setAttribute("id","social" + socialcount);
    profileEditForm.setAttribute("name","social"+ socialcount);
    profileEditForm.setAttribute("maxlength","255");

    var labelSocial = document.createElement("label");
    labelSocial.setAttribute("for","social" + socialcount);
    labelSocial.innerHTML = "Social Media Link: ";
    socialarray.push(labelSocial);
    proform.insertBefore(labelSocial, profsubmit);
    proform.insertBefore(profileEditForm,profsubmit);

    var nbreak = document.createElement("br");
    proform.insertBefore(nbreak, profsubmit);
    socialarray.push(profileEditForm);
    socialarray.push(nbreak);
    //social media name
    var socialname = document.createElement("input");
    socialname.setAttribute("type","text");
    socialname.setAttribute("id","socialname" + socialcount);
    socialname.setAttribute("name","socialname"+ socialcount);
    socialname.setAttribute("maxlength","255");

    var labelSocialName = document.createElement("label");
    labelSocialName.setAttribute("for","socialname" + socialcount);
    labelSocialName.innerHTML = "Social Media Name: ";
    socialarray.push(labelSocialName);
    proform.insertBefore(labelSocialName, profsubmit);
    proform.insertBefore(socialname,profsubmit);

    var nbreak2 = document.createElement("br");
    proform.insertBefore(nbreak2, profsubmit);
    socialarray.push(socialname);
    socialarray.push(nbreak2);
    socialcount++;
  }
  //remove function
  function removesociallink(){
    if(socialarray.length > 0){
      var rbreak = socialarray.pop();
      rbreak.remove();
      rbreak = socialarray.pop();
      rbreak.remove();
      rbreak = socialarray.pop();
      rbreak.remove();
      var rbreak = socialarray.pop();
      rbreak.remove();
      rbreak = socialarray.pop();
      rbreak.remove();
      rbreak = socialarray.pop();
      rbreak.remove();
      socialcount--;
    }

  }
//check file size and dormat client side
  function filevalidate() {
    var uploadField = document.getElementById("profileimage");
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
  </body>
</html>
