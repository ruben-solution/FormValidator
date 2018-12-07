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
    function __construct(array $fields)
    {
        $uniqueFields = [];

        foreach ($fields as $field) {
            if (!\in_array($field->getKey(), $this->keys)) {
                $this->keys[] = $field->getKey();
                $this->fields[] = $field;
            }
        }
    }

    /**
     * Get field by key
     * 
     * @param Field $field
     */
    public function addField(Field $field)
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
     * Get field by key
     * 
     * @param string $key
     * 
     * @return Field
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
     * The bar character "|" is only allowed in regex patterns.
     * So if you want to validate a date with this character in
     * the format, you can't. It's that simple
     * 
     * @param string $validationString
     * 
     * @return array
     */
    private function parseValidationString($validationString)
    {
        $parsedValidationString = $matches = [];

        preg_match_all(
            '/(?<key_value>(?P<key>[a-zA-Z]+)(:|=)\s*(?P<value>(\/.*?\/(\||$))|([^\|]+)))/',
            trim($validationString),
            $matches
        );

        foreach ($matches['key_value'] as $match) {
            $kv = preg_split('/:|=/', trim($match), 2);
            $parsedValidationString[$kv[0]] = $kv[1];
        }

        return $parsedValidationString;
    }

    /**
     * Validate form
     * 
     * @return array Returns an array with errors. if the array is empty, everything is oki-doki.
     */
    public function validate(): array
    {
        $errors = [];
        $colorFormats = ['hex', 'rgb', 'rgba', 'hsl', 'hsla'];

        foreach ($this->fields as $field) {
            $fieldKey = $field->getKey();
            $fieldValue = $field->getValue();

            $rules = $this->parseValidationString($field->getRules());

            $fieldRequired = isset($rules['required']) && $rules['required'] === 'true';
            $fieldType = isset($rules['type']) ? $rules['type'] : 'string';

            if (
                // is required
                $fieldRequired || (
                    // is not required but has value that needs to be validated
                    !$fieldRequired &&
                    (
                        (
                            $fieldType === 'string' &&
                            $fieldValue !== ''
                        ) ||
                        (
                            $fieldType === 'array' &&
                            count($fieldValue) !== 0
                        ) ||
                        (
                            $fieldType !== 'array' &&
                            $fieldType !== 'string'
                        )
                    )
                )
            ) {
                switch($fieldType) {
                    case 'number':
                        if (!Validate::isNumber($fieldValue)) {
                            $errors[] = $fieldKey;
                        } else {
                            if (
                                isset($rules['positive']) &&
                                $rules['positive'] === 'true' &&
                                !Validate::isNumberPos($fieldValue)
                            ) {
                                $errors[] = $fieldKey;
                            }

                            if (
                                isset($rules['negative']) &&
                                $rules['negative'] === 'true' &&
                                !Validate::isNumberNeg($fieldValue)
                            ) {
                                $errors[] = $fieldKey;
                            }

                            if (
                                isset($rules['nonzero']) &&
                                $rules['nonzero'] === 'true' &&
                                !Validate::isNumberNz($fieldValue)
                            ) {
                                $errors[] = $fieldKey;
                            }
                        }

                        break;
                    case 'email':
                        if (!Validate::isEmail($fieldValue)) $errors[] = $fieldKey;

                        break;
                    case 'url':
                        if (!Validate::isUrl($fieldValue)) $errors[] = $fieldKey;

                        break;
                    case 'color':
                        if (isset($rules['format']) && \in_array($rules['format'], $colorFormats)) {
                            if ($rules['format'] === 'hex') {
                                if (!Validate::isColor($fieldValue, Validate::COLOR_HEX)) $errors[] = $fieldKey;
                            } elseif ($rules['format'] === 'rgb') {
                                if (!Validate::isColor($fieldValue, Validate::COLOR_RGB)) $errors[] = $fieldKey;
                            } elseif ($rules['format'] === 'rgba') {
                                if (!Validate::isColor($fieldValue, Validate::COLOR_RGBA)) $errors[] = $fieldKey;
                            } elseif ($rules['format'] === 'hsl') {
                                if (!Validate::isColor($fieldValue, Validate::COLOR_HSL)) $errors[] = $fieldKey;
                            } elseif ($rules['format'] === 'hsla') {
                                if (!Validate::isColor($fieldValue, Validate::COLOR_HSLA)) $errors[] = $fieldKey;
                            }
                        } else {
                            if (!Validate::isColor($fieldValue)) $errors[] = $fieldKey;
                        }

                        break;
                    case 'date':
                        if (isset($rules['format'])) {
                            if (!Validate::isDate($fieldValue, $rules['format'])) $errors[] = $fieldKey;
                        } else {
                            if (!Validate::isDate($fieldValue)) $errors[] = $fieldKey;
                        }

                        break;
                    case 'array':
                        if (!Validate::isArray($fieldValue)) $errors[] = $fieldKey;

                        break;
                    case 'string':
                        if (!Validate::isStringNotEmpty($fieldValue)) {
                            $errors[] = $fieldKey;
                        } else {
                            if (
                                isset($rules['regex']) &&
                                !Validate::regex($rules['regex'], $fieldValue)
                            ) {
                                $errors[] = $fieldKey;
                            }

                            if (
                                isset($rules['max']) &&
                                \strlen($fieldValue) > (int) $rules['max']
                            ) {
                                $errors[] = $fieldKey;
                            }

                            if (
                                isset($rules['min']) &&
                                \strlen($fieldValue) < (int) $rules['min']
                            ) {
                                $errors[] = $fieldKey;
                            }
                        }

                        break;
                    default:
                        $errors[] = $fieldKey;
                }
            }
        }

        return $errors;
    }
}
