<?php

namespace StatusQuo\Model;

class Project
{
    protected $name;
    protected $basePath;
    protected $tables = [];
    protected $views = [];
    protected $reports = [];
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }
    
    
    public function addTable(Table $table)
    {
        $this->tables[$table->getId()] = $table;
        return $this;
    }
    
    public function getTables()
    {
        return $this->tables;
    }
    
    public function getTable($id)
    {
        return $this->tables[$id];
    }
    
    public function addView(View $view)
    {
        $this->views[$view->getName()] = $view;
        return $this;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function getView($name)
    {
        return $this->views[$name];
    }
    
    public function addReport(Report $report)
    {
        $this->reports[$report->getName()] = $report;
        return $this;
    }

    public function getReports()
    {
        return $this->reports;
    }

    public function getReport($name)
    {
        return $this->reports[$name];
    }
}
