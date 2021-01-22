<?php

namespace ZnLib\Telegram\Symfony4\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Telegram\Domain\Repositories\File\StoreRepository;
use ZnLib\Telegram\Domain\Repositories\Http\UpdatesRepository;
use ZnLib\Telegram\Domain\Services\LongPullService;

class LongPullCommand extends Command
{

    protected static $defaultName = 'telegram:long-pull';
    protected $longPullService;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->longPullService = new LongPullService();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Long pull</>');
        while (true) {
            $output->writeln('<fg=white>wait...</>');
            $updates = $this->longPullService->all();
            if ($updates) {
                $output->writeln('<fg=green>has updates</>');
                foreach ($updates as $update) {
                    $output->write('<fg=default> ' . $update['update_id'] . ' ... </>');
                    $this->longPullService->runBot($update);
                    $output->writeln('<fg=green>OK</>');
                }
            } else {
                $output->writeln('<fg=default>empty</>');
            }
        }
        return Command::SUCCESS;
    }
}
