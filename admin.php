<?php

/********************************************
Lohud Admin tool
by: Kai
ver 1.1

Template to build a secure admin panel with
Default timeout is 30 mins
Requires PHP 5.3+ for blowfish hashing
********************************************/


//Filter any nasty input, limit user and pass to be no more than 32 chars, shutoff error reporting
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if (strlen($_POST['u']) > 32 || strlen($_POST['p']) > 32) die();
error_reporting(0);


// //Import credentials and init variables
require('login_cred.php');
$page = "login.php";
$submitted_username = "";
$fail_message = "";


//Check to see if the user is already logged in and hasn't timed out, if so bypass login page
session_start();
if(!empty($_SESSION['user'])){
    if ($_SESSION['timeout'] + 1800 >= time()){
        $_SESSION['timeout'] = time(); 
    }
} else {
  header("Location: " . $page); 
  die("Redirecting to " . $page);
}


//Credentials are being passed through the form, see if they're legit
if(!empty($_POST)){
    foreach ($credentials as $entry) {
        if ($_POST['u']===$entry['user']){
            if (crypt($_POST['p'], $entry['pass'])===$entry['pass']){
                //Username and password good
                session_start();
                $_SESSION['user'] = $entry['name'];
                $_SESSION['timeout'] = time();
                session_write_close(); 
                header("Location: " . $page); 
                die("Redirecting to: " . $page);
            }
            else {
                //Password failed
                $submitted_username = htmlentities($_POST['u'], ENT_QUOTES, 'UTF-8');
                $fail_message = "Login Failed";
            }
        }
        else {
            //Username failed
            $submitted_username = htmlentities($_POST['u'], ENT_QUOTES, 'UTF-8');
            $fail_message = "Login Failed";
        }
    }
}



?>
<?php
  include('header.php');
?>
<style type="text/css">
  body { 
    width: 100%;
    height:100%;
    font-family: 'Open Sans', sans-serif;
    background: #f2f2f2;
/*    background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%),-moz-linear-gradient(top,  rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg,  #670d10 0%, #092756 100%);
    background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
    background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -o-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
    background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -ms-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
    background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), linear-gradient(to bottom,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), linear-gradient(135deg,  #670d10 0%,#092756 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3E1D6D', endColorstr='#092756',GradientType=1 );*/
    /*color:#f2f2f2;*/
  }

  h1, h2, h3, h4, h5, h6, p {
    /*color:#f2f2f2;*/
  }
</style>
<div class="row">
  <div class="large-12 columns">
    <div class="large-8 columns large-push-2">
    <h2 style="margin:0.5rem 0 0">Marker admin</h2>
    </div>
  </div>
</div>
<div class="row">
  <div class="large-12 columns">
    <div class="large-8 columns cards large-push-2">
      <table id="markers">
        <thead>
          <tr>
            <!-- <th>Name</th> -->
            <!-- <th>Email</th> -->
            <!-- <th>Description</th> -->
            <th>Type</th>
            <th>Pokemon</th>
            <th>Lat</th>
            <th>Lng</th>
            <th>Active</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $markers = file_get_contents('entries.json');
            $decode = json_decode($markers, true);
            // echo $decode;

            for ($i = 0; $i < count($decode); $i++){
              echo "<tr class='marker'><td contenteditable='true' data-name='title'>".$decode[$i]['Type']."</td><td contenteditable='true'>".$decode[$i]['Pokemon']."</td><td contenteditable='true'>".$decode[$i]['Lat']."</td><td contenteditable='true'>".$decode[$i]['Lng']."</td><td contenteditable='true'>".$decode[$i]['Active']."</td></tr>";
            };
          ?>          
        </tbody>
      </table>
      <button class="radius small" id="convert-table" style="color:#f2f2f2;">Save markers</button>
      <!-- <div id="debug"></div> -->
      
    </div>
  </div>
</div>
<?php
  include('footer.php');
?>