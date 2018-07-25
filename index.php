<?php 
require_once('./RedirectPOST.Class.php');

if (isset($_POST)) {
    $redirect = new RedirectPOST(CONFIG['internalServer']);
    echo $redirect->redirection($_POST);
}
