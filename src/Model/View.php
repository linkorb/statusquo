<?php

namespace StatusQuo\Model;

class View
{
    private $table;
    private $name;
    protected $filters = [];
    protected $sorts = [];
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }
    
    public function getFilters()
    {
        return $this->filters;
    }
    
    public function addSort(Sort $sort)
    {
        $this->sorts[] = $sort;
        return $this;
    }
    
    public function getSorts()
    {
        return $this->sorts;
    }
    
    public function __debugInfo()
    {
        return [
            'table' => $this->table->getId(),
            'filters' => $this->filters,
            'sorts' => $this->sorts
        ];
    }
    
    public function getRecords($variables = [])
    {
        $res = [];
        foreach ($this->table->getRecords() as $record) {
            $include = true;
            foreach ($this->getFilters() as $filter) {
                $a = $record->getData($filter->getField());
                $b = $filter->getValue();
                foreach ($variables as $key=>$value) {
                    $b = str_replace('{{' . $key . '}}', $value, $b);
                }
                
                switch ($filter->getOperator()) {
                    case 'is':
                        if ($a != $b) {
                            $include = false;
                        }
                        break;
                    case 'is any of':
                        foreach (explode(',', $b) as $key => $value) {
                            $options[trim($value)] = true;
                        }
                        if (!isset($options[$a])) {
                            $include = false;
                        }
                        break;
                    default:
                        //$include = false;
                        break;
                }
            }
            if ($include) {
                $res[] = $record;
            }
        }
        
        foreach ($this->sorts as $sort) {
            usort(
                $res,
                function ($a, $b) use ($sort) {
                    $res = strcmp(
                        $a->getData($sort->getField()),
                        $b->getData($sort->getField())
                    );
                    if (strtoupper($sort->getDirection())=='DESC') {
                        $res = -$res;
                    }
                    return $res;
                }
            );
        }
        
        return $res;
    }
}
