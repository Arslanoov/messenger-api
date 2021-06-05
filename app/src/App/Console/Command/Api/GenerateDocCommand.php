<?php

declare(strict_types=1);

namespace App\Console\Command\Api;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class GenerateDocCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('api:doc:generate')
            ->setDescription('Generates OpenAPI docs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $swagger = 'vendor/bin/openapi';
        $source = 'src/App/Http/Handler';
        $target = 'public/docs/openapi.json';

        $process = new Process([PHP_BINARY, $swagger, $source, '--output', $target]);
        $process->run(static function (string $_type, string $buffer) use ($output) {
            $output->write($buffer);
        });

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
