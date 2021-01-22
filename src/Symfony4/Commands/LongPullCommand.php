<?php

namespace ZnLib\Telegram\Symfony4\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZnCore\Base\Exceptions\InternalServerErrorException;
use ZnCore\Base\Helpers\EnvHelper;
use ZnLib\Telegram\Domain\Repositories\File\ConfigRepository;
use ZnLib\Telegram\Domain\Services\LongPullService;

class LongPullCommand extends Command
{

    protected static $defaultName = 'telegram:long-pull';
    protected $longPullService;
    protected $configRepository;

    public function __construct(string $name = null, LongPullService $longPullService, ConfigRepository $configRepository)
    {
        parent::__construct($name);
        $this->longPullService = $longPullService;
        $this->configRepository = $configRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Long pull</>');
        $output->writeln('<fg=white>timeout:</> <fg=yellow>' . $this->configRepository->getBotConfig('timeout', 5) . ' second</>');
        while (true) {
            if (EnvHelper::isDebug()) {
                $output->writeln('<fg=white>wait...</>');
            }
            $updates = $this->longPullService->all();
            if ($updates) {
                //$output->writeln('<fg=green>has updates</>');
                foreach ($updates as $update) {
                    $output->write('<fg=default> ' . $update['update_id'] . ' ... </>');
                    try {
                        $this->longPullService->runBot($update);
                        $output->writeln('<fg=green>OK</>');
                    } catch (InternalServerErrorException $e) {
                        $output->writeln('<fg=red>FAIL</>');
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
