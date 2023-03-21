<?php

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\Actions\Like\CreateLike;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\AddComment;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\CreatePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\DeletePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Post\FindByUuid;
use GeekBrains\LevelTwo\Blog\Http\Actions\User\CreateUser;
use GeekBrains\LevelTwo\Blog\Http\Actions\User\FindByUsername;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

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
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
    ],
    'POST' => [
        '/posts/create' => CreatePost::class,
        '/users/create' => CreateUser::class,
        '/posts/comment' => AddComment::class,
        '/posts/like' => CreateLike::class,
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];

if (
    !array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])
) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

$actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
