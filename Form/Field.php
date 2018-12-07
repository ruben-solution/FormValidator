<?php

namespace Form;

class Field
{
    private $key;
    private $value;
    private $rules;
    private $allowedValues;
    private $name;

    /**
     * @param string       $key
     * @param string|array $value
     * @param string       $rules
     * @param array        $allowedValues
     * @param string       $name
     */
    function __construct(
        $key,
        $value=null,
        $rules,
        $allowedValues=array(),
        $name=''
    )
    {
        $this->key = $key;
        $this->value = $value;
        $this->rules = $rules;
        $this->allowedValues = $allowedValues;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string|array|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $rules
     */
    public function getRules(): string
    {
        return $this->rules;
    }

    /**
     * @return string
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param string $allowedValues
     */
    public function getAllowedValues(): string
    {
        return $this->allowedValues;
    }

    /**
     * @return string
     */
    public function setAllowedValues($allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
