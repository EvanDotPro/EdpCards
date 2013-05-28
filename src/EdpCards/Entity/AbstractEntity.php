<?php
namespace EdpCards\Entity;

abstract class AbstractEntity
{
    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return null;
    }
}
