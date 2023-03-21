<?php

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Person\Person;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;

include __DIR__ . '/vendor/autoload.php';

//$faker = Faker\Factory::create();
switch ($argv[1]) {
        //Пока кастомный лоадер фейкер отключен
    case 'user':
        echo new User(UUID::random(), new Name('Fake', 'User'), 'Fake');
        break;
    case 'post':
        echo new Post(UUID::random(), new User(UUID::random(), new Name('Fake', 'User'), 'Unknown'), 'FakeTitle', 'FakePost');
        break;
    case 'comment':
        $commentUser = new User(UUID::random(), new Name('Comment', 'User'), 'CommentUser');
        echo new Comment(UUID::random(), $commentUser, new Post(UUID::random(), $commentUser, 'FakePostTitle', 'FakePostText'), 'FakeComment');
        break;
}

$name = new Name('Peter', 'Sidorov');
$firstUuid = UUID::random();
$user = new User($firstUuid, $name, 'Admin');


$person = new Person($name, new DateTimeImmutable());


$post = new Post(
    UUID::random(),
    $user,
    'Приветствие',
    'Всем привет!'
);

$secondUuid = UUID::random();
$user2 = new User($secondUuid, new Name('Ivan', 'Taranov'), 'User');

$userRepository = new InMemoryUsersRepository();
$userRepository->save($user);
$userRepository->save($user2);

try {
    $userRepository->get($firstUuid);
    $userRepository->get($secondUuid);
    $userRepository->get(UUID::random());
} catch (UserNotFoundException $userNotFoundException) {
    echo $userNotFoundException->getMessage();
}
