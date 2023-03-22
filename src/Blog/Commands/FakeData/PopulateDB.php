<?php

namespace GeekBrains\LevelTwo\Blog\Commands\FakeData;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInteface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    // Внедряем генератор тестовых данных и
    // репозитории пользователей и статей
    public function __construct(
        private \Faker\Generator $faker,
        private UserRepositoryInterface $usersRepository,
        private PostsRepositoryInteface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Number of fake users',
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Number of fake posts',
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $usersNumber = $input->getOption('users-number');
        $postsNumber = $input->getOption('posts-number');

        if (empty($usersNumber)) {
            $usersNumber = 10;
        }

        if (empty($postsNumber)) {
            $postsNumber = 10;
        }

        $users = [];
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }

        $posts = [];
        foreach ($users as $user) {
            for ($i = 0; $i < $postsNumber; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln($i . '. Post created: ' . $post->getTitle());
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < 2; $i++) {
                $comment = $this->createFakeComment($post);
                $output->writeln($i . '. Comment created: ' . $comment->getText());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeComment(Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post->getAuthor(),
            $post,
            $this->faker->sentence(3, true)
        );
        $this->commentsRepository->save($comment);
        return $comment;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->password,
            new Name(
                $this->faker->firstName,
                $this->faker->lastName
            )
        );
        $this->usersRepository->save($user);
        return $user;
    }
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );
        $this->postsRepository->save($post);
        return $post;
    }
}
