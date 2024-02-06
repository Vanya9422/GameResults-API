<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Result;
use App\Models\User;
use App\Service\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TopListControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function testGetTopResultsWithoutEmail()
    {
        $this->withoutExceptionHandling();

        // Создаем 10 фейковых результатов для теста
        Result::factory(10)->create();

        // Мок сервиса для возвращения фейковых результатов
        $resultServiceMock = $this->createMock(ResultService::class);
        $resultServiceMock->method('getTopResults')->willReturn(Result::orderByDesc('milliseconds')->take(10)->get());

        // Связываем мок с сервис-контейнером Laravel
        $this->app->instance(ResultService::class, $resultServiceMock);

        // Делаем GET-запрос без передачи email
        $response = $this->getJson('api/results/top');

        // Проверяем успешный ответ
        $response->assertStatus(200);

        // Проверяем, что 'top' ключ существует в JSON-ответе
        $response->assertJsonStructure([
            'data' => [
                'top',
            ],
        ]);

        // Проверяем, что 'top' содержит 10 элементов
        $response->assertJsonCount(10, 'data.top');
    }

    public function testGetTopResultsWithEmail()
    {
        $this->withoutExceptionHandling();

        // Создаем 10 фейковых результатов для теста
        Result::factory(10)->create();

        // Создаем фейковый результат для self, который будем передавать в запросе
        $selfResult = Member::factory()->create();

        // Мок сервиса для возвращения фейковых результатов
        $resultServiceMock = $this->createMock(ResultService::class);
        $resultServiceMock->method('getTopResults')->willReturn(
            new Collection(Result::orderByDesc('milliseconds')->take(10)->get()->toArray())
        );

        $resultServiceMock->method('getUserBestResult')
            ->with($selfResult->email)
            ->willReturn([
                'id' => $selfResult->id,
                'email' => $selfResult->email,
                'place' => $selfResult->place,
                'milliseconds' => $selfResult->milliseconds,
            ]);

        // Связываем мок с сервис-контейнером Laravel
        $this->app->instance(ResultService::class, $resultServiceMock);

        // Делаем GET-запрос с передачей email
        $response = $this->getJson('api/results/top?email=' . $selfResult->email);

        // Проверяем успешный ответ
        $response->assertStatus(200);

        // Проверяем структуру JSON-ответа
        $response->assertJsonStructure([
            'data' => [
                'top',
                'self',
            ],
        ]);

        // Проверяем, что 'top' содержит 10 элементов
        $response->assertJsonCount(10, 'data.top');

        // Проверяем, что 'self' имеет ожидаемую структуру
        $response->assertJson([
            'data' => [
                'self' => [
                    'id' => $selfResult->id,
                    'email' => $selfResult->email,
                    'place' => $selfResult->place,
                    'milliseconds' => $selfResult->milliseconds
                ],
            ],
        ]);
    }
}
