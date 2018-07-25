<?php
/**
 * Abstract class contains main methods that will be used to specify redirection 
 * @var string $destination hidden server
 * @var array $config config variables
 * @var resource $ch cURL session  
 */
abstract class Redirect {
    
    protected $destination;
    protected $config;
    protected $ch;

    /**
     * Define vars
     * @param string $destination hidden server
     */
    abstract public function __construct($destination);

    /**
     * Has to close cURL session
     */
    abstract public function __destruct();

    /**
     * Filter which  resources can have access to destination
     */
    abstract protected function filterOrigin();

    /**
     * Geting request data and pass it to remote api on destination server
     * @param string $data has to be JSON string 
     */
    abstract public function redirection($data);

}