<?php
header('Content-Type: application/json');
//Sanitize against XSS
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


if( !empty( $_POST ) ){
    $postArray = array(
      // "Name" => $_POST['name'],
      // "Email" => $_POST['email'],
      // "Description" => $_POST['description'],
      "Type" => $_POST['type'],
      "Lat" => $_POST['lat'],
      "Lng" => $_POST['lng'],
      "Pokemon" => $_POST['pokemon'],
      "Active" => "Yes",
    );

$file = file_get_contents('entries.json');

if (!empty($file)){
$decode = json_decode($file, true);
array_push( $decode, $postArray);
$json = json_encode ($decode);
file_put_contents('entries.json', $json);
} else {
$array = array($postArray);
$encode = json_encode($array);
file_put_contents('entries.json', $encode);	
}

// echo $json;
} 

?>