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
    <form method="post" id = "profileform" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      Email: <input type="text" id="emailedit" name="emailedit" maxlength="128"><br>
      Phone Number: <input type="tel" id="phoneedit" name="phoneedit" placeholder="000-000-0000"
      pattern="[\d]{3}-[\d]{3}-[\d]{4}"><br>
      Fax Number: <input type="tel" id="faxedit" name="faxedit" placeholder="000-000-0000"
      pattern="[\d]{3}-[\d]{3}-[\d]{4}"><br>

      <script>
      var socialcount = 0;
      var socialarray = new Array();
      function addsociallink(){
        var proform = document.getElementById('profileform');
        var profsubmit = document.getElementById('addsocial');
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
        socialcount++;
      }
      function removesociallink(){
        if(socialarray.length > 0){
          var rbreak = socialarray.pop();
          rbreak.remove();
          rbreak = socialarray.pop();
          rbreak.remove();
          rbreak = socialarray.pop();
          rbreak.remove();
          socialcount--;
        }
      }
      </script>
      <button type="button" id="addsocial" onclick="addsociallink()"> Add Social Media</button><br>
      <button type="button" id="removesocial" onclick="removesociallink()">Remove Social Media</button><br>
      <textarea id="profiledesc" name="profiledesc" rows="8" cols="75" maxlength="1025"></textarea>
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
          $usercontact;
          $stmt = $conn->stmt_init();
          $query = "SELECT contactId FROM users WHERE idUsers = ?";
          $stmt->prepare($query);
          $stmt->bind_param("s",$curruserId);
          $stmt->execute();
          $stmt->bind_result($contactexists);
          $stmt->fetch();

          if(!is_null($contactexists)){
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
            SET userDesc = ?
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){

            }
            else{
              $stmt->bind_param("ss",$profiledesc,$curruserId);
              $stmt->execute();
            }

            $stmt = $conn->stmt_init();
            $query = "DELETE sm FROM socialmedia sm
            INNER JOIN contactsm csm ON sm.idSocialMedia = csm.idSocialMedia
            INNER JOIN contactinformation ci ON ci.idContactInformation = csm.idContactInformation
            INNER JOIN users u ON ci.idContactInformation = u.contactId
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("s",$curruserId);
              $stmt->execute();
            }
          }
          else{
            $stmt = $conn->stmt_init();

            $query = "INSERT INTO contactinformation(email,phoneNumber,FaxNumber) VALUES(?,?,?)";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("sss",$email,$phonenumber,$faxnumber);
              $stmt->execute();
            }
            $stmt = $conn->stmt_init();

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
            SET userDesc = ?
            WHERE u.idUsers = ?";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param("ss",$profiledesc,$curruserId);
              $stmt->execute();
            }
          }
          $socialArray = array();
          $soccount = 0;
          while(isset($_POST['social' . $soccount])){
             $socialArray.array_push($_POST['social' . $soccount]);
             $soccount++;
          }
          $soccount = 0;
          for($i = 0;$i < count($socialArray);$i++){
            $stmt->stmt_init();
            $query = "INSERT INTO socialmedia(socialMediaLink)
            VALUES (?)";
            $stmt->prepare($query);
            $stmt->bind_param($socialArray[i]);
            $stmt->execute();
            $stmt->stmt_init();

            $query = "SELECT last_insert_id() FROM socialmedia";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->execute();
              $stmt->bind_result($lastsmres);
              $stmt->fetch();
            }

            $query = "INSERT INTO contactsm(idContactInformation, idSocialMedia)
            SELECT u.contactId, ? FROM users u WHERE ";
            if(!$stmt->prepare($query)){
              echo "query failed";
            }
            else{
              $stmt->bind_param(s, $curruserId);
              $stmt->execute();
            }
          }
      }

      $conn->close();
    ?>
  </body>
</html>
