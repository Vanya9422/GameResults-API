<?php

namespace App\Http\Controllers\Result;

use App\Http\Requests\Result\StoreResultRequest;
use App\Http\Resources\Result\ResultResource;
use App\Http\Responses\ResponseBuilder;
use App\Service\ResultService;
use Illuminate\Http\JsonResponse;

class StoreController {

    /**
     * Обрабатывает запрос на сохранение результата.
     *
     * @param StoreResultRequest $request
     * @param ResultService $resultService
     * @return JsonResponse|ResultResource
     */
    public function __invoke(
        StoreResultRequest $request,
        ResultService $resultService
    ): JsonResponse|ResultResource {

        try {

            $validatedData = $request->validated();

            $result = $resultService->storeResult(
                $validatedData['email'] ?? null, $validatedData['milliseconds']
            );

            // Используйте ResultResource для форматирования результата
            return new ResultResource($result);
        } catch (\Exception $e) {
            // Логирование исключения
            \Log::error('Error storing result: ' . $e->getMessage());

            // Возвращение ответа с сообщением об ошибке
            return ResponseBuilder::serverError('Error occurred while storing the result.');
        }
    }
}
