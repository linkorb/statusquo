<?php

namespace StatusQuo\Loader;

use StatusQuo\Model\Project;
use StatusQuo\Model\Table;
use StatusQuo\Model\Field;
use StatusQuo\Model\View;
use StatusQuo\Model\Filter;
use StatusQuo\Model\Sort;
use StatusQuo\Model\Report;


use RuntimeException;

class ProjectLoader
{
    
    public function loadFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        if (!$data) {
            throw new RuntimeException("Failed to load project (json error): " . $filename);
        }
        $basePath = dirname($filename);
        return $this->load($data, $basePath);
    }
    
    public function load($data, $basePath)
    {
        $project = new Project();
        $project->setName($data['name']);
        $project->setBasePath($basePath);
        foreach ($data['tables'] as $tableData) {
            $table = new Table();
            $table->setId($tableData['id']);
            $table->setSourceType($tableData['sourceType']);
            $table->setSourceParameters($tableData['sourceParameters']);
            
            foreach ($tableData['fields'] as $fieldData) {
                $field = new Field();
                $field->setName($fieldData['name']);
                if (isset($fieldData['map'])) {
                    $field->setMap($fieldData['map']);
                }
                $table->addField($field);
            }

            $project->addTable($table);
        }
        
        foreach ($data['views'] as $viewData) {
            $view = new View();
            $table = $project->getTable($viewData['table']);
            $view->setTable($table);
            $view->setName($viewData['name']);
            
            
            if (isset($viewData['filters'])) {
                foreach ($viewData['filters'] as $filterData) {
                    $filter = new Filter();
                    $filter->setField($filterData['field']);
                    $filter->setOperator($filterData['operator']);
                    $filter->setValue($filterData['value']);
                    $view->addFilter($filter);
                }
            }
            if (isset($viewData['sorts'])) {
                foreach ($viewData['sorts'] as $sortData) {
                    $sort = new Sort();
                    $sort->setField($sortData['field']);
                    if (isset($sortData['direction'])) {
                        $sort->setDirection($sortData['direction']);
                    }
                    $view->addSort($sort);
                }
            }
    
            $project->addView($view);
        }
        
        foreach ($data['reports'] as $reportData) {
            $report = new Report();
            $report->setName($reportData['name']);
            $report->setVariables($reportData['variables']);
            $report->setTemplate($reportData['template']);
            $project->addReport($report);
        }
        return $project;
    }
}
