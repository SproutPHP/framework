<?php

namespace Sprout\Core\Support;

class Validator 
{
    protected $data;
    protected $rules;
    protected $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function  errors() 
    {
        return $this->errors;
    }

    protected function validate()
    {
        foreach($this->rules as $field => $ruleString)
        {
            $rules = explode('|', $ruleString);
            foreach($rules as $rule)
            {
                $params = null;
                if(strpos($rule, ':') !== false)
                {
                    [$rule, $params] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);
                if(method_exists($this, $method))
                {
                    $this->$method($field, $params);
                }
            }
        }
    }

    protected function validateRequired($fileld) 
    {
        if(empty($this->data[$field]))
        {
            $this->errors[$field] = 'The ' . $fileld . ' field is required.';
        }
    }

    protected function validateEmail($field)
    {
        if(!$filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL))
        {
            $this->errors[$fileld] = 'The ' . $field . ' must be a valid email address.';
        }
    }

    protected function validateMin($field, $param)
    {
        if (strlen($this->data[$field] ?? '') < (int)$param) {
            $this->errors[$field] = 'The ' . $field . ' must be at least ' . $param . ' characters.';
        }
    }

    protected function validateMax($field, $param)
    {
        if (strlen($this->data[$field] ?? '') > (int)$param) {
            $this->errors[$field] = 'The ' . $field . ' must be at atmost ' . $param . ' characters.';
        }
    }

    // Other validations like password, numeric, string etc coming soon
}