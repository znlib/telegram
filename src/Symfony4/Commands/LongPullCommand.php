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
        $output->writeln('<fg=white>timeout:</> <fg=yellow>' . $this->configRepository->getLongpullTimeout() . ' second</>');
        while (true) {
            if (EnvHelper::isDebug()) {
                $output->writeln('<fg=white>wait...</>');
            }
            $updates = $this->longPullService->all();
            if ($updates) {
                //$output->writeln('<fg=green>has updates</>');
                foreach ($updates as $update) {
//                    dd($update['message']['from']['id']);
                    if(!empty($update['message'])) {
                        $line = 'message ' . $update['update_id'] . ' from ' . $update['message']['chat']['id'];
                        if(isset($update['message']['chat']['username'])) {
                            $line .= ' (@' . $update['message']['chat']['username'] . ')';
                        } elseif ($update['message']['chat']['first_name']) {
                            $line .= ' (' . $update['message']['chat']['first_name'] . ')';
                        }
                        $output->write('<fg=default> ' . $line . ' ... </>');
                        try {
                            $this->longPullService->runBotFromService($update);
                            $output->writeln('<fg=green>OK</>');
                        } catch (\Throwable $e) {
                            $this->longPullService->setHandled($update);
                            $output->writeln('<fg=red>FAIL '.$e->getMessage().'</>');
                        }
                    } elseif(!empty($update['channel_post'])) {
                        $line = 'channel post ' . $update['update_id'] . ' from ' . $update['channel_post']['chat']['id'];
                        if ($update['channel_post']['chat']['title']) {
                            $line .= ' (' . $update['channel_post']['chat']['title'] . ')';
                        }
                        $output->write('<fg=default> ' . $line . ' ... </>');
                        $output->writeln('<fg=yellow>SKIP</>');
                        $this->longPullService->setHandled($update);
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
