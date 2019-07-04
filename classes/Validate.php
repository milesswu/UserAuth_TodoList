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
                $value = trim($source[$item]);
                $item = escape($item);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if (strlen($value) < $rule_val) {
                                $this->addError("{$item} must be a minimum of {$rule_val} characters");
                            }
                        break;

                        case 'max':
                            if (strlen($value) > $rule_val) {
                                $this->addError("{$item} must be a maximum of {$rule_val} characters");
                        }
                        break;

                        case 'matches':
                            if ($value != $source[$rule_val]) {
                                $this->addError("{$rule_val} must match {$item}");
                            }
                        break;

                        case 'unique':
                            $check = $this->db->get($rule_val, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("This {$item} already exists!");
                            }
                        break;

                        default:
                        break;
                    }
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