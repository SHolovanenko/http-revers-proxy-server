<?php 
require_once('./RedirectPOST.Class.php');

if (isset($_POST)) {
    $redirect = new RedirectPOST('http://local-project-3.loc');
    echo $redirect->redirection($_POST);
}
