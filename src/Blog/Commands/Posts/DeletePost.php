<?php

namespace GeekBrains\LevelTwo\Blog\Commands\Posts;

use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInteface;
use GeekBrains\LevelTwo\Blog\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class DeletePost extends Command
{
    public function __construct(
        private PostsRepositoryInteface $postsRepository,
    ) {
        parent::__construct();
    }
    // Конфигурируем команду
    protected function configure(): void
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Deletes a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $question = new ConfirmationQuestion(
            // Вопрос для подтверждения
            'Delete post [Y/n]? ',
            // По умолчанию не удалять
            false
        );
        // Ожидаем подтверждения
        if (!$this->getHelper('question')
            ->ask($input, $output, $question)) {
            // Выходим, если удаление не подтверждено
            return Command::SUCCESS;
        }
        // Получаем UUID статьи
        $uuid = new UUID($input->getArgument('uuid'));
        // Удаляем статью из репозитория
        $this->postsRepository->deletePost($uuid);
        $output->writeln("Post $uuid deleted");
        return Command::SUCCESS;
    }
}
