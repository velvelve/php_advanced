<?php

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

include __DIR__ . '/vendor/autoload.php';


$container = require __DIR__ . '/bootstrap.php';
// При помощи контейнера создаём команду
$command = $container->get(CreateUserCommand::class);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    echo "{$e->getMessage()}\n";
}

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');


$usersRepository = new SqliteUsersRepository($connection);
$postRepository = new SqlitePostsRepository($connection);
$commentRepository = new SqliteCommentsRepository($connection);

echo "Hello world";
