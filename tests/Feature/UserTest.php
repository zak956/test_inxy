<?php

declare(strict_types=1);

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @param array $data
     * @param int $expectedResponse
     * @return void
     *
     * @dataProvider getDataForCreate
     */
    public function testCreate(array $data, int $expectedResponse): void
    {
        $response = $this->post('api/user', $data, $this->getHeaders());
        $response->assertStatus($expectedResponse);
        if ($expectedResponse == Response::HTTP_CREATED) {
            $expectedData = [
                'data' => $data
            ];

            $response->assertJson($expectedData);
        }
    }

    /**
     * @param int $id
     * @param int $expectedResponse
     * @return void
     *
     * @dataProvider getDataForView
     */
    public function testView(int $id, int $expectedResponse): void
    {
        $response = $this->get('api/user/' . $id, $this->getHeaders());
        $response->assertStatus($expectedResponse);
    }

    /**
     * @param int $id
     * @param array $data
     * @param int $expectedResponse
     * @return void
     *
     * @dataProvider getDataForUpdate
     */
    public function testUpdate(int $id, array $data, int $expectedResponse): void
    {
        $response = $this->patch('api/user/' . $id, $data, $this->getHeaders());
        $response->assertStatus($expectedResponse);
        if ($expectedResponse == Response::HTTP_OK) {
            $expectedData = [
                'data' => $data
            ];

            $response->assertJson($expectedData);
        }
    }

    /**
     * @return array[]
     */
    public static function getDataForView(): array
    {
        return [
            [
                1001,
                Response::HTTP_OK
            ],
            [
                666,
                Response::HTTP_NOT_FOUND
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function getDataForCreate(): array
    {
        return [
            [
                [
                    'name' => 'User Name',
                    'email' => 'user@email.com',
                    'age' => 30
                ],
                Response::HTTP_CREATED
            ],
            [
                [
                    'name' => 'User2 Name',
                    'email' => 'user2@email.com',
                    'age' => 7
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                [
                    'name' => 'User2 Name',
                    'email' => 'not a email',
                    'age' => 30
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                [
                    'name' => 'User3 Name',
                    'email' => 'user3@email.com'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function getDataForUpdate(): array
    {
        return [
            [
                1001,
                [
                    'name' => 'User New Name',
                    'email' => 'user_new@axample.com',
                    'age' => 19
                ],
                Response::HTTP_OK
            ],
            [
                2001,
                [
                    'name' => 'User New Name',
                    'email' => 'user_new2@axample.com',
                    'age' => 19
                ],
                Response::HTTP_NOT_FOUND
            ],
            [
                1002,
                [
                    'name' => 'User Failed Name',
                    'email' => 'not a email',
                    'age' => 30
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
        ];
    }
}
