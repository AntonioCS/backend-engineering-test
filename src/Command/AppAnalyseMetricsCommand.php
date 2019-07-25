<?php declare(strict_types=1);

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\DataAnalyzer\DataAnalyzer;
use App\Service\DataAnalyzer\Result;
use App\Service\DataAnalyzer\Formatters\TextFormatter;

/**
 * Class AppAnalyseMetricsCommand
 *
 * @package App\Command
 */
class AppAnalyseMetricsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:analyse-metrics';

    /**
     * @var DataAnalyzer
     */
    private $dataAnalyzer;

    /**
     * @var TextFormatter;
     */
    private $textFormatter;

    public function __construct(DataAnalyzer $dataAnalyzer, TextFormatter $textFormatter)
    {
        $this->dataAnalyzer = $dataAnalyzer;
        $this->textFormatter = $textFormatter;

        parent::__construct();
    }


    /**
     * Configure the command.
     */
    protected function configure(): void
    {
        $this->setDescription('Analyses the metrics to generate a report.');
        $this->addOption('input', null, InputOption::VALUE_REQUIRED, 'The location of the test input');
    }

    /**
     * Detect slow-downs in the data and output them to stdout.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $metricsFile = $input->getOption('input');

        if ($metricsFile && \is_file($metricsFile)) {
            $this->dataAnalyzer->loadFileData($metricsFile);
            $result = $this->dataAnalyzer->processData();

            $output->write($this->textFormatter->output($result));
        }
        else {
            throw new \LogicException('Missing data');
        }

    }
}
