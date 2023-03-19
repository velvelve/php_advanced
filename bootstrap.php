<?php
// Подключаем автозагрузчик Composer

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\LikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\SqliteLikeRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInteface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostsRepositoryInteface::class,
    SqlitePostsRepository::class
);

$container->bind(
    UserRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    SqliteCommentsRepository::class
);

$container->bind(
    LikeRepositoryInterface::class,
    SqliteLikeRepository::class
);

return $container;
