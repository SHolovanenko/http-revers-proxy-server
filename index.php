<?php 
require_once('./RedirectPOST.Class.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    
    $rawHeaders = getallheaders();
    $headers = [];

    foreach ($rawHeaders as $key => $value) {
        $headers[] = $key. ': ' .$value; 
    }
    
    $redirect = new RedirectPOST(CONFIG['internalServer']);
    echo $redirect->redirection($data, $headers);
}
