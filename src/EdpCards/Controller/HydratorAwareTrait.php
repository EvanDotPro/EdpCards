<?php
namespace EdpCards\Controller;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\View\Model\JsonModel;

trait HydratorAwareTrait
{
    protected $hydrator;

    protected function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new Hydrator;
        }

        return $this->hydrator;
    }

    protected function jsonModel($value)
    {
        return new JsonModel($this->toArray($value));
    }

    protected function toArray($value)
    {
        if (is_array($value) or $value instanceof \Traversable) {
            $return = [];
            foreach ($value as $key => $item) {
                $return[$key] = $this->toArray($item);
            }
            return $return;
        } else if (is_object($value)) {
            return $this->toArray($this->getHydrator()->extract($value));
        }
        return $value;
    }
}
