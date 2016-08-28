<?php

namespace StatusQuo\Model;

class Report
{
    protected $name;
    protected $template;
    protected $variables = [];
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function getVariables()
    {
        return $this->variables;
    }
    
    public function setVariables($variables)
    {
        $this->variables = $variables;
        return $this;
    }
    
}
