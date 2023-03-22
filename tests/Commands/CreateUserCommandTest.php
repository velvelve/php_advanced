<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\Blog\UnitTests\DummyLogger;
use GeekBrains\LevelTwo\Blog\Commands\Users\CreateUser;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\DummyUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserCommandTest extends TestCase
{

    public function testItRequiresPassword(): void
    {
        $command = new CreateUser(
            $this->makeUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "first_name, last_name, password"'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
            ]),
            new NullOutput()
        );
    }


    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {

        $command = new CreateUserCommand(new DummyUsersRepository(), new DummyLogger());

        $this->expectException(CommandException::class);

        $this->expectExceptionMessage('User already exists: Ivan');

        $command->handle(new Arguments(['username' => 'Ivan', 'password' => 'test']));
    }

    public function testItRequiresFirstName(): void
    {

        $command = new CreateUser(
            $this->makeUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "first_name, last_name").'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
            ]),
            new NullOutput()
        );
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUser(
            $this->makeUsersRepository(),
        );
        // Меняем тип ожидаемого исключения ..
        $this->expectException(RuntimeException::class);
        // .. и его сообщение
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "last_name").'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
                'first_name' => 'Ivan',
            ]),
            new NullOutput()
        );
    }


    public function testItSavesUserToRepository(): void
    {

        $usersRepository = $this->createMock(UserRepositoryInterface::class);

        $command = new CreateUser(
            $usersRepository
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',
            ]),
            new NullOutput()
        );
    }



    private function makeUsersRepository(): UserRepositoryInterface
    {
        return new class implements UserRepositoryInterface
        {
            public function save(User $user): void
            {
            }
            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }
        };
    }
}
