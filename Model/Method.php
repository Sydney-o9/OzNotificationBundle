<?php

namespace merk\NotificationBundle\Model;


abstract class Method implements MethodInterface
{

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }




}
