<?php

namespace StatusQuo\Model;

class Table
{
    protected $id;
    protected $sourceType;
    protected $sourceParameters = [];
    protected $fields = [];
    protected $records = [];
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getSourceType()
    {
        return $this->sourceType;
    }
    
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;
        return $this;
    }
    
    public function getSourceParameters()
    {
        return $this->sourceParameters;
    }
    
    public function setSourceParameters($sourceParameters)
    {
        $this->sourceParameters = $sourceParameters;
        return $this;
    }
    
    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }
    
    public function hasField($name)
    {
        return isset($this->fields[$name]);
    }
    
    public function getField($name)
    {
        if (!$this->hasField($name)) {
            throw new RuntimeException("No such field: " . $name);
        }
        return $this->fields[$name];
    }
    
    public function getFields()
    {
        return $this->fields;
    }
    
    public function getRecords()
    {
        return $this->records;
    }
    
    public function setRecords($records)
    {
        $this->records = $records;
        return $this;
    }
}
