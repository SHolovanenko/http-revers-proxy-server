<?php
define('CONFIG', require_once('./config.php'));
require_once('./Abstract.Redirect.Class.php');

/**
 * Redirect POST request
 * @var string $destination hidden server
 * @var array $config config variables
 * @var resource $ch cURL session  
 */
class RedirectPOST extends Redirect {

    /**
     * Define vars
     * @param string $destination hidden server
     */
    public function __construct($destination) {
        if (isset($_GET['path'])) {
            $destination .= '/' .$_GET['path'];
        }
        $this->destination  = $destination;
        $this->config       = CONFIG;
        $this->ch           = curl_init();
    }

    /**
     * Close cURL session
     */
    public function __destruct() {
        curl_close($this->ch);
    }

    /**
     * Filter which  resources can have access to destination
     * 
     * @return bool
     */
    protected function filterOrigin() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if ($this->config['whiteListPOST'] == '*' ) {
                return true;
            }

            foreach ($this->config['whiteListPOST'] as $whiteOrigin) {
                if (stripos($_SERVER['HTTP_ORIGIN'], $whiteOrigin) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Geting request data and pass it to remote api on destination server
     * @param string $data has to be JSON string 
     * 
     * @return string
     */
    public function redirection($data) {;
        if  (!$this->filterOrigin()) {
            //header("HTTP/1.0 404 Not Found");
            return null;
        }
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        
        curl_setopt($this->ch, CURLOPT_URL, $this->destination);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        
        $server_output = curl_exec($this->ch);
        
        if (curl_errno($this->ch)) {
            $server_output = 'Error:' . curl_error($this->ch);
        }

        return $server_output;
    }
}