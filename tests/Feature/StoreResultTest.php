<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Service\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class StoreResultTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    // WithoutMiddleware, если нужно игнорировать проверку CSRF
    public function testStoreValidResult()
    {
        // Создаем фейковую модель Result, которую будет возвращать мок сервиса
        $fakeResult = Result::factory()->make();

        // Мок сервиса для возвращения фейковой модели
        $resultServiceMock = $this->createMock(ResultService::class);
        $resultServiceMock->method('storeResult')->willReturn($fakeResult);

        // Связываем мок с сервис-контейнером Laravel
        $this->app->instance(ResultService::class, $resultServiceMock);

        // Передаем валидные данные в запрос
        $response = $this->postJson('/api/results', [
            'email' => 'test@example.com',
            'milliseconds' => 12345
        ]);

        // Проверяем, что ответ корректен и соответствует структуре ResultResource
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $fakeResult->id,
                    'member_id' => $fakeResult->member_id,
                    'milliseconds' => $fakeResult->milliseconds,
                    'created_at' => $fakeResult->created_at,
                ]
            ]);
    }

    public function testStoreResultServerError() {
        $this->withoutExceptionHandling();

        // Мок сервиса для имитации исключения
        $resultServiceMock = $this->createMock(ResultService::class);
        $resultServiceMock->method('storeResult')
            ->will($this->throwException(new \Exception));

        // Связываем мок с сервис-контейнером Laravel
        $this->app->instance(ResultService::class, $resultServiceMock);

        // Передаем данные, которые вызовут исключение
        $response = $this->postJson('api/results', [
            'email' => 'test@example.com',
            'milliseconds' => 12345
        ]);

        // Проверяем, что возвращается ошибка сервера
        $response->assertStatus(500)
            ->assertJson([
                'error' => 'Error occurred while storing the result.'
            ]);
    }
}
