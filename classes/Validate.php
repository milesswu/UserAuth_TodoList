<?php
class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db = null;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function check($source, $items = array()) {
        //item represents field, rules represents the array the item maps to
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_val) {
                $value = $source[$item];

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } 
            }
        }

        //check whether info passed all tests
        if (empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }

    public function passed() {
        return $this->_passed;
    }

    public function errors() {
        return $this->_errors;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }


}

?>