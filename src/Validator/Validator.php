<?php

namespace  Plutuss\SauceCore\Validator;

class   Validator implements ValidatorInterface
{
    private array $errors = [];

    private array $data;

    /**
     * @param array $data
     * @param array $rules
     * @return bool
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        $this->data = $data;

        foreach ($rules as $key => $ruleItem) {
            foreach ($ruleItem as $rule) {
                $rule = explode(':', $rule);

                $ruleName = $rule[0];
                $ruleValue = $rule[1] ?? null;

                $error = $this->validateRule($key, $ruleName, $ruleValue);

                if ($error) {
                    $this->errors[$key][] = $error;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function validatedData(): array
    {
        if (empty($this->errors())) {
            return $this->data;
        }
        return [];
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $key
     * @param string $ruleName
     * @param string|null $ruleValue
     * @return string|false
     */
    private function validateRule(string $key, string $ruleName, string $ruleValue = null): string|false
    {
        $value = $this->data[$key];

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    return "Field $key is required";
                }
                break;
            case 'min':
                if (strlen($value) < $ruleValue) {
                    return "Field $key must be at least $ruleValue characters long";
                }
                break;
            case 'max':
                if (strlen($value) > $ruleValue) {
                    return "Field $key must be at most $ruleValue characters long";
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "Field $key must be a valid email address";
                }
                break;
            case 'float':
                if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    return "Field $key must be a valid float";
                }
                break;
            case 'int':
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    return "Field $key must be a valid int";
                }
                break;
            case 'string':
                if (!is_string($value)) {
                    return "Field $key must be a valid string";
                }
                break;
            case 'confirmed':
                if ($value !== $this->data["{$key}_confirmation"]) {
                    return "Field $key must be confirmed";
                }
                break;
        }

        return false;
    }
}
