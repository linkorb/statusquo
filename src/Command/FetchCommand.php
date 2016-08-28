<?php

namespace StatusQuo\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use StatusQuo\Loader\ProjectLoader;
use StatusQuo\Fetcher\AirtableFetcher;
use StatusQuo\Fetcher\ListbaseFetcher;
use StatusQuo\ReportRenderer;
use RuntimeException;

class FetchCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('fetch')
            ->setDescription('Fetch data from sources')
            ->addOption(
                'project',
                'p',
                InputOption::VALUE_REQUIRED,
                null
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectPath = $input->getOption('project');
        if (!$projectPath) {
            $projectPath = getcwd() . '/statusquo.json';
        }
        $output->writeLn("Updating project: " . $projectPath);
        $projectLoader = new ProjectLoader();
        $project = $projectLoader->loadFile($projectPath);
        //var_dump($project);
        
        foreach ($project->getTables() as $table) {
            switch ($table->getSourceType()) {
                case 'airtable':
                    $fetcher = new AirtableFetcher();
                    $records = $fetcher->fetch($table);
                    $table->setRecords($records);
                    //var_dump($records);
                    break;
                case 'listbase':
                    $fetcher = new ListbaseFetcher();
                    $records = $fetcher->fetch($table);
                    $table->setRecords($records);
                    //var_dump($records);
                    break;
                default:
                    throw new RuntimeException("Unknown table source type: " . $table->getSourceType());
                    break;
            }
        }
        
        $view = $project->getView('allcards');
        //var_dump($view);
        $records = $view->getRecords();
        /*
        echo "RECORDS:\n";
        //var_dump($records);
        foreach ($records as $record) {
            echo $record->getData('id') . ': ' . $record->getData('Name') . "\n";
        }
        */
        
        $reportRenderer = new ReportRenderer($project);
        foreach ($project->getReports() as $report) {
            echo $reportRenderer->render($report->getName());
        }
        
    }
}
