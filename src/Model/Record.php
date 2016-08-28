<?php

namespace StatusQuo\Model;

use RuntimeException;

class Record
{
    private $table;
    protected $url;
    protected $data = [];
    
    public function getId()
    {
        return '#' . $this->data['id'];
    }
    public function getTable()
    {
        return $this->table;
    }
    
    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    
    
    public function setData($key, $value)
    {
        if (!$this->table->hasField($key)) {
            throw new RuntimeException("Undefined field on table: " . $this->table->getId() . '.' . $key);
        }
        $field = $this->table->getField($key);
        $map = $field->getMap();
        if (isset($map[$value])) {
            $value = $map[$value];
        }
        $this->data[$key] = $value;
    }
    
    public function getData($key)
    {
        if (!isset($this->data[$key])) {
            return null;
        }
        return $this->data[$key];
    }
    
    public function __debugInfo()
    {
        return [
            'table' => $this->table->getId(),
            'url' => $this->url,
            'data' => $this->data,
        ];
    }
    
    public function __call($name, $args)
    {
        if (substr($name, 0, 3)=='get') {
            $key = substr($name, 3);
            return $this->getData($key);
        }
        return $this->getData($name);
    }
}
