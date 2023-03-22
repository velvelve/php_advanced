<?php

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
// При помощи контейнера создаём команду
$command = $container->get(CreateUserCommand::class);
$logger = $container->get(LoggerInterface::class);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    echo "{$e->getMessage()}\n";
}

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');


$usersRepository = new SqliteUsersRepository($connection, $logger);
$postRepository = new SqlitePostsRepository($connection, $logger);
$commentRepository = new SqliteCommentsRepository($connection, $logger);

echo "Hello world";
