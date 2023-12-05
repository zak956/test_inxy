<?php

declare(strict_types=1);

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccountTest extends TestCase
{
    /**
     * @param int $id
     * @param array $data
     * @param int $expectedResponse
     * @return void
     *
     * @dataProvider getDataForFill
     */
    public function testFill(int $id, array $data, int $expectedResponse): void
    {
        $response = $this->post(sprintf('api/account/%d/fill', $id), $data, $this->getHeaders());
        $response->assertStatus($expectedResponse);
        if ($expectedResponse == Response::HTTP_CREATED) {
            $expectedData = [
                'data' => [
                    'sender_id' => null,
                    'recipient_id' => $id,
                    'amount' => $data['amount'],
                    'title' => 'Filling balance.'
                ]
            ];

            $response->assertJson($expectedData);
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @param int $expectedResponse
     * @return void
     *
     * @dataProvider getDataForTransfer
     */
    public function testTransfer(int $id, array $data, int $expectedResponse): void
    {
        $response = $this->post(sprintf('api/account/%d/send', $id), $data, $this->getHeaders());
        $response->assertStatus($expectedResponse);
        if ($expectedResponse == Response::HTTP_CREATED) {
            $expectedData = [
                'data' => [
                    'sender_id' => $id,
                    'recipient_id' => $data['recipient_id'],
                    'amount' => $data['amount'],
                    'title' => $data['title']
                ]
            ];

            $response->assertJson($expectedData);
        }
    }

    /**
     * @return array
     */
    public static function getDataForTransfer(): array
    {
        return [
            [
                101,
                [
                    'recipient_id' => 102,
                    'amount' => '5.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_CREATED
            ],
            [
                105,
                [
                    'recipient_id' => 102,
                    'amount' => '5.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_NOT_FOUND
            ],
            [
                101,
                [
                    'recipient_id' => 102,
                    'amount' => '0.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                101,
                [
                    'recipient_id' => 105,
                    'amount' => '5.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                101,
                [
                    'recipient_id' => 102,
                    'amount' => '-5.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                101,
                [
                    'recipient_id' => 102,
                    'amount' => '100.00',
                    'title' => 'Money transfer'
                ],
                Response::HTTP_BAD_REQUEST
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getDataForFill(): array
    {
        return [
            [
                101,
                [
                    'amount' => '5.00'
                ],
                Response::HTTP_CREATED
            ],
            [
                105,
                [
                    'amount' => '5.00'
                ],
                Response::HTTP_NOT_FOUND
            ],
            [
                101,
                [
                    'amount' => '0.00'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                101,
                [
                    'amount' => '-5.00'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
        ];
    }
}
