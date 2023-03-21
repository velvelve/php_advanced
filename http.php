<?php

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\AddComment;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\CreatePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\DeletePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\FindByUuid;
use GeekBrains\LevelTwo\Blog\Http\Actions\User\CreateUser;
use GeekBrains\LevelTwo\Blog\Http\Actions\User\FindByUsername;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'),);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}
try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}
$routes = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/show' => new FindByUuid(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
    'POST' => [
        '/posts/create' => new CreatePost(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/users/create' => new CreateUser(
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
        '/posts/comment' => new AddComment(
            new SqliteCommentsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            ),
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
            )
        ),
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found method'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found path'))->send();
    return;
}

$action = $routes[$method][$path];
try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
