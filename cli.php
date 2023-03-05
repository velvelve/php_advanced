<?php

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

include __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');


$usersRepository = new SqliteUsersRepository($connection);
$postRepository = new SqlitePostsRepository($connection);
$commentRepository = new SqliteCommentsRepository($connection);

try {
    $user = new User(UUID::random(), new Name('Alex', 'Ivanov'), 'Admin');

    $usersRepository->save($user);

    $postUuid = UUID::random();

    $post = new Post($postUuid, $user, 'Title', 'Post text');

    $postRepository->save($post);

    $commentUuid = UUID::random();

    $comment = new Comment($commentUuid, $user, $post, "Comment text");

    $commentRepository->save($comment);
    echo $commentRepository->get($commentUuid);
} catch (CommandException $e) {
    echo "{$e->getMessage()}\n";
}
