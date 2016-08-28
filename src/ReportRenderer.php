<?php

namespace StatusQuo;

use StatusQuo\Model\Project;
use LightnCandy\LightnCandy;
use RuntimeException;

class ReportRenderer
{
    protected $project;
    
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
    
    public function render($reportName)
    {
        $report = $this->project->getReport($reportName);
        
        $loader = new \Twig_Loader_Filesystem($this->project->getBasePath());
        $twig = new \Twig_Environment($loader, []);
        
        
        $context = [
            'project' => $this->project,
            'variables' => $report->getVariables()
        ];
        
        $html = $twig->render($report->getTemplate(), $context);
        return $html;
    }
}
