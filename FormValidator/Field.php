<?php

namespace FormValidator;

/**
 * @author Ruben Allenspach <ruben.allenspach@solution.ch>
 */
class Field
{
    private $key;
    private $value;
    private $rules;
    private $name;

    /**
     * @param string       $key
     * @param string|array $value
     * @param string|array $rules
     * @param string       $name
     */
    function __construct(
        $key,
        $value=null,
        $rules,
        $name=''
    )
    {
        $this->key = $key;
        $this->value = $value;
        $this->rules = $rules;
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
     * @param string|array $rules
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return string|array
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
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
