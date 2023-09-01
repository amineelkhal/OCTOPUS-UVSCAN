<?php
/*
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.cloud.adaptiverecognition.com/vehicle/afr");
curl_setopt($ch, CURLOPT_HTTPHEADER , array(
    "Content-Type: multipart/form-data",
    "X-Api-Key: kYQhj3VUCC9R3QIDtqjif9kFYMl0LCRG3A1MDVvd",
    "Accept: application/json"
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "service=anpr;image=@test.jpg;type=image/jpg");
//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('image' => '@test.jpg', 'service'=>'anpr,mmr')));
// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
echo $server_output;

curl_close($ch);
*/

/*
$curl = curl_init();

$path = 'h786poj.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = base64_encode($data);

print $type;

$data = array(
    "image" => "@$base64;type=image/" . $type,
    "service"=>"anpr",
    'maxreads'=>'1'
);

$boundary = uniqid();
$payload = buildPayload($boundary, $data);

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.cloud.adaptiverecognition.com/vehicle/afr",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $payload,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: multipart/form-data boundary=$boundary",
    "X-Api-Key: kYQhj3VUCC9R3QIDtqjif9kFYMl0LCRG3A1MDVvd",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

function buildPayload($boundary, $data) {
    $payload = '';
    foreach ($data as $name => $value) {
        $payload .= "--$boundary\r\n";
        $payload .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
        $payload .= "$value\r\n";
    }
    $payload .= "--$boundary--\r\n";
    return $payload;
}

/*$output = escapeshellcmd('anpr.py');
echo $output;*/

//$pythonFile = 'java -version';
//$arguments = 'arg1 arg2 arg3';

//print exec($pythonFile, $output, $returnValue);
/*
print $returnValue;

if ($returnValue == 0) {
    print_r( $output );
    foreach ($output as $line) {
        echo $line . "<br>";
    }
} else {
    //print_r($output);
    echo  "Failed to run Python script";
}*/
/*
$output = shell_exec("timeout 1000m py anpr.py");
echo $output;*/

$path = 'h786poj.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = base64_encode($data);

print $base64;

?>
