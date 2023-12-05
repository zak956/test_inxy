<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $repository = $this->getRepositoryMock();

        $repository->expects($this->once())->method('create');
        $repository->expects($this->never())->method('find');
        $repository->expects($this->never())->method('save');

        $service = new UserService($repository);
        $service->create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'age' => 33
        ]);
    }

    /**
     * @param array $data
     * @return void
     *
     * @dataProvider getDataForUpdate
     */
    public function testUpdate(array $data): void
    {
        $initial = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'age' => 33
        ];

        $user = new User($initial);

        $repository = $this->getRepositoryMock();

        $repository->expects($this->never())->method('create');
        $repository->expects($this->never())->method('find');
        $repository->expects($this->once())->method('save')->willReturnArgument(0);

        $service = new UserService($repository);
        $userUpdated = $service->update($user, $data);
        foreach (['name', 'email', 'age'] as $key) {
            self::assertEquals($userUpdated->$key, array_key_exists($key, $data) ? $data[$key] : $initial[$key]);
        }
    }

    /**
     * @return UserRepositoryInterface&MockObject
     */
    protected function getRepositoryMock(): UserRepositoryInterface&MockObject
    {
        return $this->getMockBuilder(UserRepository::class)
            ->onlyMethods(['create', 'find', 'save'])
            ->getMock();
    }

    /**
     * @return array[]
     */
    public static function getDataForUpdate(): array
    {
        return [
            [
                [
                    'name' => 'New Name'
                ]
            ],
            [
                [
                    'email' => 'new@test.com'
                ]
            ],
            [
                [
                    'age' => '50'
                ]
            ],
            [
                [
                    'name' => 'New Name',
                    'email' => 'new@test.com',
                    'age' => '50'
                ]
            ],
        ];
    }
}
