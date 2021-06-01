<?php

declare(strict_types=1);

namespace App\Console\Command\User;

use App\Service\ValidatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\CreateAdmin\Command as CreateCommand;
use User\UseCase\CreateAdmin\Handler;

final class CreateAdminCommand extends Command
{
    private Handler $handler;
    private ValidatorInterface $validator;

    public function __construct(Handler $handler, ValidatorInterface $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('user:admin')
            ->setDescription('Create admin user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $username = (string) $helper->ask($input, $output, new Question('Username: '));
        $password = (string) $helper->ask($input, $output, new Question('Password: '));

        $command = new CreateCommand($username, $password);

        try {
            $this->validator->validate($command);
        } catch (ValidationFailedException $e) {
            $violations = $e->getViolations();
            if ($violations->count()) {
                foreach ($violations as $violation) {
                    $output->writeln(
                        '<error>' . $violation->getPropertyPath() . ': ' . $violation->getMessage() . '</error>'
                    );
                }
                return Command::FAILURE;
            }
        }

        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
