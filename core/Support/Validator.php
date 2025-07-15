<?php

namespace Core\Support;

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
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            foreach ($rules as $rule) {
                $params = null;
                if (strpos($rule, ':') !== false) {
                    [$rule, $params] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    $this->$method($field, $params);
                }
            }
        }
    }

    /**
     * Field must not be empty
     */
    protected function validateRequired($field)
    {
        if (empty($this->data[$field])) {
            $this->errors[$field] = "The $field field is required.";
        }
    }

    /**
     * Field must be a valid email address
     */
    protected function validateEmail($field)
    {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "The $field must be a valid email address.";
        }
    }

    /**
     * Minimum lenght
     */
    protected function validateMin($field, $param)
    {
        if (strlen($this->data[$field] ?? '') < (int)$param) {
            $this->errors[$field] = "The $field must be at least $param characters.";
        }
    }

    /**
     * Maximum length
     */
    protected function validateMax($field, $param)
    {
        if (strlen($this->data[$field] ?? '') > (int)$param) {
            $this->errors[$field] = "The $field must be at atmost $param characters.";
        }
    }

    /**
     * Field must be numeric
     */
    protected function validateNumeric($field, $param)
    {
        if (!is_numeric($this->data[$field] ?? null)) {
            $this->errors[$field] = "The $field must be a number";
        }
    }

    /**
     * Field must be an integer
     */
    protected function validateInteger($field)
    {
        if (!filter_var($this->data[$field] ?? null, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "The $field must be an integer.";
        }
    }

    /**
     * Field must be a string
     */
    protected function validateString($field)
    {
        if (!is_string($this->data[$field] ?? null)) {
            $this->errors[$field] = "The $field must be a string.";
        }
    }

    /**
     * Field must be a boolean
     */
    protected function validateBoolean($field)
    {
        $value = $this->data[$field] ?? null;
        if (!in_array($value, [true, false, 0, 1, '0', '1'], true)) {
            $this->errors[$field] = "This $field must be a boolean.";
        }
    }

    /**
     * Field must be an array
     */
    protected function validateArray($field)
    {
        if (!is_array($this->data[$field] ?? null)) {
            $this->errors[$field] = "The $field must be an array.";
        }
    }

    /**
     * In: Value must be one of the listed options
     */
    protected function validateIn($field, $param)
    {
        $options = explode(',', $param);
        if (!in_array($this->data[$field] ?? null, $options)) {
            $this->errors[$field] = "The $field must be one of: " . implode(', ', $options) . ".";
        }
    }

    /**
     * Not In: Value must NOT be one of the listed options
     */
    protected function validateNot_in($field, $param)
    {
        $options = explode(',', $param);
        if (in_array($this->data[$field] ?? null, $options)) {
            $this->errors[$field] = "The $field must not be one of: " . implode(', ', $options) . ".";
        }
    }

    /**
     * Same: Must match another field
     */
    protected function validateSame($field, $param)
    {
        if (($this->data[$field] ?? null) !== ($this->data[$param] ?? null)) {
            $this->errors[$field] = "The $field must match $param.";
        }
    }

    /**
     * Different: Must be different from another field
     */
    protected function validateDifferent($field, $param)
    {
        if (($this->data[$field] ?? null) === ($this->data[$param] ?? null)) {
            $this->errors[$field] = "The $field must be different from $param.";
        }
    }

    /**
     * Confirmed: Must have a matching {field}_confirmation value
     */
    protected function validateConfirmed($field)
    {
        if (($this->data[$field] ?? null) !== ($this->data[$field . '_confirmation'] ?? null)) {
            $this->errors[$field] = "The $field confirmation does not match.";
        }
    }

    /**
     * Regex: Must match a regex pattern
     */
    protected function validateRegex($field, $param)
    {
        if (!preg_match('/' . $param . '/', $this->data[$field] ?? '')) {
            $this->errors[$field] = "The $field format is invalid.";
        }
    }

    /**
     * URL: Must be a valid URL
     */
    protected function validateUrl($field)
    {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "The $field must be a valid URL.";
        }
    }

    /**
     * IP: Must be a valid IP address
     */
    protected function validateIp($field)
    {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_IP)) {
            $this->errors[$field] = "The $field must be a valid IP address.";
        }
    }

    /**
     * Date: Must be a valid date
     */
    protected function validateDate($field)
    {
        if (strtotime($this->data[$field] ?? '') === false) {
            $this->errors[$field] = "The $field must be a valid date.";
        }
    }

    /**
     * Before: Must be a date before the given date
     */
    protected function validateBefore($field, $param)
    {
        $value = strtotime($this->data[$field] ?? '');
        $compare = strtotime($param);
        if ($value === false || $compare === false || $value >= $compare) {
            $this->errors[$field] = "The $field must be a date before $param.";
        }
    }

    /**
     * After: Must be a date after the given date
     */
    protected function validateAfter($field, $param)
    {
        $value = strtotime($this->data[$field] ?? '');
        $compare = strtotime($param);
        if ($value === false || $compare === false || $value <= $compare) {
            $this->errors[$field] = "The $field must be a date after $param.";
        }
    }

    /**
     * Nullable: Field is allowed to be null (affects other rules)
     */
    protected function validateNullable($field)
    {
        // No error, just a marker for other rules to skip if null
    }

    /**
     * Present: Field must be present in the input (even if empty)
     */
    protected function validatePresent($field)
    {
        if (!array_key_exists($field, $this->data)) {
            $this->errors[$field] = "The $field field must be present.";
        }
    }

    /**
     * Digits: Must be exactly N digits
     */
    protected function validateDigits($field, $param)
    {
        if (!preg_match('/^\d{' . (int)$param . '}$/', $this->data[$field] ?? '')) {
            $this->errors[$field] = "The $field must be exactly $param digits.";
        }
    }

    /**
     * Digits Between: Must be between min and max digits
     */
    protected function validateDigits_between($field, $param)
    {
        [$min, $max] = explode(',', $param);
        $length = strlen($this->data[$field] ?? '');
        if (!preg_match('/^\d+$/', $this->data[$field] ?? '') || $length < (int)$min || $length > (int)$max) {
            $this->errors[$field] = "The $field must be between $min and $max digits.";
        }
    }

    /**
     * Size: Must be exactly N characters (for strings) or N value (for numbers/arrays)
     */
    protected function validateSize($field, $param)
    {
        $value = $this->data[$field] ?? null;
        if (is_array($value)) {
            $size = count($value);
        } elseif (is_numeric($value)) {
            $size = $value;
        } else {
            $size = strlen($value);
        }
        if ($size != (int)$param) {
            $this->errors[$field] = "The $field must be exactly $param in size.";
        }
    }

    /**
     * Starts With: Must start with one of the given values
     */
    protected function validateStarts_with($field, $param)
    {
        $options = explode(',', $param);
        $value = $this->data[$field] ?? '';
        $valid = false;
        foreach ($options as $option) {
            if (strpos($value, $option) === 0) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            $this->errors[$field] = "The $field must start with one of: " . implode(', ', $options) . ".";
        }
    }

    /**
     * Ends With: Must end with one of the given values
     */
    protected function validateEnds_with($field, $param)
    {
        $options = explode(',', $param);
        $value = $this->data[$field] ?? '';
        $valid = false;
        foreach ($options as $option) {
            if (substr($value, -strlen($option)) === $option) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            $this->errors[$field] = "The $field must end with one of: " . implode(', ', $options) . ".";
        }
    }

    /**
     * UUID: Must be a valid UUID
     */
    protected function validateUuid($field)
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $this->data[$field] ?? '')) {
            $this->errors[$field] = "The $field must be a valid UUID.";
        }
    }
}
