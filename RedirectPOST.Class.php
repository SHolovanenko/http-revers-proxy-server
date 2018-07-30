<?php
define('CONFIG', require_once('./config.php'));
require_once('./Abstract.Redirect.Class.php');

/**
 * Redirect POST request
 * @var string $destination hidden server
 * @var array $config config variables
 * @var resource $ch cURL session  
 */
class RedirectPOST {

    /**
     * Define vars
     * @param string $destination hidden server
     */
    public function __construct(string $destination) {
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
     * Filter of user headers
     * @param array $headers user headers
     */
    protected function filterHeaders(array $headers) {
        $filtered = [];
        foreach (CONFIG['userHeaders'] as $userHeaderAllowed) {
            foreach ($headers as $key => $header) {
                if (stripos($userHeaderAllowed, $header) !== false) {
                    $filtered[] = $header;
                }
            }
        }

        return $filtered;
    }

    /**
     * Geting request data and pass it to remote api on destination server
     * @param string $data has to be JSON string  
     * @param array $header headers array geted from client
     * 
     * @return string
     */
    public function redirection(string $data, array $headers) {;
        if  (!$this->filterOrigin()) {
            //header("HTTP/1.0 404 Not Found");
            return null;
        }
        
        $headers = $this->filterHeaders($headers);

        curl_setopt($this->ch, CURLOPT_URL, $this->destination);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        
        $server_output = curl_exec($this->ch);
        
        if (curl_errno($this->ch)) {
            $server_output = 'Error:' . curl_error($this->ch);
        }
        
        //$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        //$header = substr($server_output, 0, $header_size);
        //$body = substr($server_output, $header_size);

        return $server_output;
    }
}