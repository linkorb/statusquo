<?php

namespace StatusQuo\Model;

class Field
{
    protected $name;
    protected $map = [];
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getMap()
    {
        return $this->map;
    }
    
    public function setMap($map)
    {
        $this->map = $map;
        return $this;
    }
}
