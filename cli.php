<?php

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Person\Person;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;

spl_autoload_register('load');
//include __DIR__ . '/vendor/autoload.php';

function load($className)
{
    $file = $className . ".php";
    $file = str_replace("\\", "/", $file);
    $file = str_replace("GeekBrains/LevelTwo", "src/", $file);
    if (file_exists($file)) {
        include $file;
    }
}

//$faker = Faker\Factory::create();
switch ($argv[1]) {
        //Пока кастомный лоадер фейкер отключен
    case 'user':
        echo new User(11, new Name('Fake', 'User'), 'Fake');
        break;
    case 'post':
        echo new Post(11, new User(11, new Name('Fake', 'User'), 'Unknown'), 'FakeTitle', 'FakePost');
        break;
    case 'comment':
        $commentUser = new User(11, new Name('Comment', 'User'), 'CommentUser');
        echo new Comment(11, $commentUser, new Post(11, $commentUser, 'FakePostTitle', 'FakePostText'), 'FakeComment');
        break;
}

$name = new Name('Peter', 'Sidorov');
$user = new User(1, $name, 'Admin');


$person = new Person($name, new DateTimeImmutable());


$post = new Post(
    1,
    $user,
    'Приветствие',
    'Всем привет!'
);

$user2 = new User(2, new Name('Ivan', 'Taranov'), 'User');

$userRepository = new InMemoryUsersRepository();
$userRepository->save($user);
$userRepository->save($user2);

try {
    $userRepository->get(1);
    $userRepository->get(2);
    $userRepository->get(3);
} catch (UserNotFoundException $userNotFoundException) {
    echo $userNotFoundException->getMessage();
}
