<?php
class Error {
    private $errors;
    public function __construct() {
        $this->errors = array();
    }
    public function addError($error , $message) {  
        if (isset($this->errors[$error])) {
            $this->errors[$error] = array();
        }
        array_push($this->errors[$error] , $message);
    }
    public function getErrors():array {
        return $this->errors; 
    }
}

?>