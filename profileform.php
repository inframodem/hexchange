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
    <form method="post" id = "profileform" action= <?php echo(htmlspecialchars($_SERVER["PHP_SELF"]));?>>
      Email: <input type="text" id="emailedit" name="emailedit" maxlength="128"><br>
      Phone Number: <input type="text" id="phoneedit" name="phoneedit" maxlength="15"><br>
      Fax Number: <input type="text" id="faxedit" name="faxedit" maxlength="15"><br>
      <button type="button" id="addsocial" onclick="addsocial()"> Add Social Media</button><br>
      <button type="button" id="removesocial" onclick="removesocial()">Remove Social Media</button><br>
      <script>
      var socialcount = 0;
      var socialarray = new Array();
      function addsocial(){
        var proform = document.getElementById('profileform');
        var profsubmit = document.getElementById('profilesubmit');
        var profileEditForm = document.createElement("input");
        profileEditForm.setAttribute("type","text");
        profileEditForm.setAttribute("id","social" + socialcount);
        profileEditForm.setAttribute("name","social"+ socialcount);
        proform.insertBefore(profileEditForm,profsubmit);
        var nbreak = document.createElement("br");
        proform.insertBefore(nbreak, profsubmit);
        socialarray.push(profileEditForm);
        socialarray.push(nbreak);
        socialcount++;
      }
      function removesocial(){
        var rbreak = socialarray.pop();
        rbreak.remove();
        rbreak = socialarray.pop();
        rbreak.remove();
        socialcount--;
      }
      </script>
      <textarea id="profiledesc" name="profiledesc" rows="8" cols="75">
      </textarea>
      <br><br>
      <input type="submit" value = "Submit">
    </form>
  </body>
</html>
