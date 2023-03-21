<?php

namespace GeekBrains\LevelTwo\Person;

class Person
{

    private Name $name;
    private \DateTimeImmutable $registeredOn;

    public function __construct(Name $name, \DateTimeImmutable $registeredOn)
    {
        $this->name = $name;
        $this->registeredOn  = $registeredOn;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of registeredOn
     */
    public function getRegisteredOn()
    {
        return $this->registeredOn;
    }

    /**
     * Set the value of registeredOn
     *
     * @return  self
     */
    public function setRegisteredOn($registeredOn)
    {
        $this->registeredOn = $registeredOn;

        return $this;
    }
}
