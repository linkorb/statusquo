<?php

namespace StatusQuo\Model;

class Sort
{
    protected $field;
    protected $direction;
    
    public function getField()
    {
        return $this->field;
    }
    
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }
    
    public function getDirection()
    {
        return $this->direction;
    }
    
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }
}
