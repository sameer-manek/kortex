<?php

class Validation
{
    private $_passed = false,
            $_errors = array(),
            $_db = null;

    public function __construct(){
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()){
        foreach ($items as $item => $rules){
            foreach ($rules as $rule => $value){
                if($rule === "required" && empty($source[$item])){
                    $this->addError( $item,"Marked field is required ");
                    return $this;
                } else if(!empty($value)){
                    switch ($rule){
                        case 'min':
                            if(strlen($source[$item]) < $value){
                                $this->addError($item, "The marked field requires minimum of {$value} characters ");
                                return $this;
                            }
                        break;

                        case 'max':
                            if(strlen($source[$item]) > $value){
                                $this->addError($item, "Sorry, maximum {$value} characters only!");
                                return $this;
                            }
                        break;

                        case 'matches':
                            if($source[$item] != $source[$value]){
                                $this->addError($item, "Passwords do not match ");
                                return $this;
                            }
                        break;

                        case 'type':
                            if($value === 'email' && !filter_var($source[$item], FILTER_VALIDATE_EMAIL)){
                                $this->addError($item, "Please add a valid email");
                                return $this;
                            }
                        break;

                        case 'unique':
                            $check = $this->_db->get($value, array($item, '=', $source[$item]));
                            if($check->count()){
                                $this->addError($item, "The email is already registered!");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->_errors)){
            $this->_passed = true;
        }

        return $this;
    }

    public function addError($item, $error){
        $this->_errors[] = array(
            $item => $error,
        );
    }

    public function error(){
        return $this->_errors;
    }

    public function passed(){
        return $this->_passed;
    }
}