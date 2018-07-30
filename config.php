<?php
return array(
    'internalServer' => 'local-project-3.loc',

    //can be set '*' to allow all domains
    'whiteListPOST' => array(
        'remote-server.loc',
        'local-project.loc'
    ),

    'userHeaders' => array(
        'Content-Length',
        'Content-type',
        'Accept-Encoding'
    )
);