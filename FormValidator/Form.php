<?php

namespace FormValidator;

use FormValidator\Validate;
use FormValidator\Field;

/**
 * @author Ruben Allenspach <ruben.allenspach@solution.ch>
 */
class Form
{
    /** @var Field[] $fields */
    private $fields = [];

    /** @var array $keys */
    private $keys = [];

    /**
     * @param Field[] $fields
     */
    function __construct(array $fields=[])
    {
        foreach ($fields as $field) {
            if (!\in_array($field->getKey(), $this->keys)) {
                $this->keys[] = $field->getKey();
                $this->fields[] = $field;
            }
        }
    }

    /**
     * Add field
     *
     * @param Field $field
     */
    public function addField(Field $field): bool
    {
        if (!\in_array($field->getKey(), $this->keys)) {
            $this->keys[] = $field->getKey();
            $this->fields[] = $field;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Add multiple fields
     *
     * @param array $fields
     */
    public function addFields(array $fields): bool
    {
        $success = true;

        foreach ($fields as $field) {
            if (!$this->addField($field)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Get field by key
     *
     * @param string $key
     *
     * @return Field|bool
     */
    public function getField($key)
    {
        foreach ($this->fields as $field) {
            if ($key === $field->getKey()) {
                return $field;
            }
        }

        return false;
    }

    /**
     * Parse validation string. Output is key-value array of rule name and value
     *
     * @param string $validationString
     *
     * @return array
     */
    private function parseValidationString($validationString): array
    {
        $rules = [];

        $rule_pairs = preg_split(
            '/\s*\|\s*(?=required|regex|max|min|format|type|nonzero)/',
            trim($validationString)
        );

        foreach ($rule_pairs as $rule_pair) {
            $kv = preg_split('/:|=/', trim($rule_pair), 2);
            $rules[trim($kv[0])] = trim($kv[1]);
        }

        return $rules;
    }

    /**
     * Handles validation of number
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateNumber($value, $rules): bool
    {
        $hasError = false;

        if (!Validate::isNumber($value)) {
            $hasError = true;
        } else {
            if (
                isset($rules['positive']) &&
                $rules['positive'] === 'true' &&
                !Validate::isNumberPos($value)
            ) {
                $hasError = true;
            }

            if (
                isset($rules['negative']) &&
                $rules['negative'] === 'true' &&
                !Validate::isNumberNeg($value)
            ) {
                $hasError = true;
            }

            if (
                isset($rules['nonzero']) &&
                $rules['nonzero'] === 'true' &&
                !Validate::isNumberNz($value)
            ) {
                $hasError = true;
            }

            if (
                isset($rules['max']) &&
                \floatval($value) > \floatval($rules['max'])
            ) {
                $hasError = true;
            }

            if (
                isset($rules['min']) &&
                \floatval($value) < \floatval($rules['min'])
            ) {
                $hasError = true;
            }
        }

        return !$hasError;
    }

    /**
     * Handles validation of string
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateString($value, $rules): bool
    {
        $hasError = false;

        if (!Validate::isStringNotEmpty($value)) {
            $hasError = true;
        } else {
            if (
                isset($rules['regex']) &&
                !Validate::regex($rules['regex'], $value)
            ) {
                $hasError = true;
            }

            if (
                isset($rules['max']) &&
                \strlen($value) > (int) $rules['max']
            ) {
                $hasError = true;
            }

            if (
                isset($rules['min']) &&
                \strlen($value) < (int) $rules['min']
            ) {
                $hasError = true;
            }
        }

        return !$hasError;
    }

    /**
     * Handles validation of color
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateColor($value, $rules): bool
    {
        $hasError = false;

        $colorFormats = ['hex', 'rgb', 'rgba', 'hsl', 'hsla'];

        if (isset($rules['format']) && \in_array($rules['format'], $colorFormats)) {
            if ($rules['format'] === 'hex') {
                if (!Validate::isColor($value, Validate::COLOR_HEX)) {
                    $hasError = true;
                }
            } elseif ($rules['format'] === 'rgb') {
                if (!Validate::isColor($value, Validate::COLOR_RGB)) {
                    $hasError = true;
                }
            } elseif ($rules['format'] === 'rgba') {
                if (!Validate::isColor($value, Validate::COLOR_RGBA)) {
                    $hasError = true;
                }
            } elseif ($rules['format'] === 'hsl') {
                if (!Validate::isColor($value, Validate::COLOR_HSL)) {
                    $hasError = true;
                }
            } elseif ($rules['format'] === 'hsla') {
                if (!Validate::isColor($value, Validate::COLOR_HSLA)) {
                    $hasError = true;
                }
            }
        } else {
            if (!Validate::isColor($value)) {
                $hasError = true;
            }
        }

        return !$hasError;
    }

    /**
     * Handles validation of date
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateDate($value, $rules): bool
    {
        $hasError = false;

        if (isset($rules['format'])) {
            if (!Validate::isDate($value, $rules['format'])) {
                $hasError = true;
            }
        } else {
            if (!Validate::isDate($value)) {
                $hasError = true;
            }
        }

        return !$hasError;
    }

    /**
     * Handles validation of email
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateEmail($value, $rules): bool
    {
        $hasError = false;

        if (!Validate::isEmail($value)) {
            $hasError = true;
        }

        return !$hasError;
    }

    /**
     * Handles validation of URL
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateURL($value, $rules): bool
    {
        $hasError = false;

        if (!Validate::isUrl($value)) {
            $hasError = true;
        }

        return !$hasError;
    }

    /**
     * Handles validation of array
     *
     * @param string $value
     * @param array  $rules
     *
     * @return bool
     */
    private function validateArray($value, $rules): bool
    {
        $hasError = false;

        if (!Validate::isArray($value)) {
            $hasError = true;
        } else {
            if (
                isset($rules['max']) &&
                (int) $rules['max'] < count($value)
            ) {
                $hasError = true;
            }

            if (
                isset($rules['min']) &&
                (int) $rules['min'] > count($value)
            ) {
                $hasError = true;
            }
        }

        return !$hasError;
    }

    /**
     * Validate form
     *
     * @return array Returns an array with errors. if the array is empty, everything is oki-doki.
     */
    public function validate(): array
    {
        $errors = [];

        foreach ($this->fields as $field) {
            $fieldKey   = $field->getKey();
            $fieldValue = $field->getValue();
            $rules      = (
                is_array($field->getRules()) ?
                    $field->getRules() :
                    $this->parseValidationString($field->getRules())
            );

            if (
                // if rules holds fixed values
                is_array($field->getRules())
            ) {
                if (!in_array($fieldValue, $rules)) {
                    $errors[] = $fieldKey;
                }

                continue;
            }

            $fieldRequired = isset($rules['required']) && $rules['required'] === 'true';
            $fieldType     = isset($rules['type']) ? $rules['type'] : 'string';

            if (
                // is required
                $fieldRequired || (
                    // is not required but has value that needs to be validated
                    !$fieldRequired && (
                        // field type is string and value is not empty
                        (
                            $fieldType  === 'string' &&
                            $fieldValue !== ''
                        ) ||
                        // OR field type is array and has values
                        (
                            $fieldType         === 'array' &&
                            count($fieldValue) !== 0
                        ) ||
                        // OR field type is not array nor string and value is not empty
                        (
                            $fieldType  !== 'array' &&
                            $fieldType  !== 'string' &&
                            $fieldValue !== ''
                        )
                    )
                )
            ) {
                switch ($fieldType) {
                    case 'number':
                        if (!$this->validateNumber($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'email':
                        if (!$this->validateEmail($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'url':
                        if (!$this->validateURL($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'color':
                        if (!$this->validateColor($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'date':
                    case 'datetime':
                    case 'time':
                        if (!$this->validateDate($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'array':
                        if (!$this->validateArray($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    case 'string':
                        if (!$this->validateString($fieldValue, $rules)) {
                            $errors[] = $fieldKey;
                        }

                        break;
                    default:
                        $errors[] = $fieldKey;
                }
            }
        }

        return $errors;
    }

    /**
     * Returns array with all fields and their values
     *
     * @return array
     */
    public function getFields(): array
    {
        $fields = [];

        foreach ($this->fields as $field) {
            $fields[] = [
                'key'   => $field->getKey(),
                'value' => $field->getValue(),
                'rules' => $field->getRules(),
                'name'  => $field->getName()
            ];
        }

        return $fields;
    }
}
