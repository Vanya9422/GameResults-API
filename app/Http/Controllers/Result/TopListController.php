<?php

namespace App\Http\Controllers\Result;

use App\Http\Responses\ResponseBuilder;
use App\Service\ResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopListController {

    /**
     * Обрабатывает запрос на получение топ-10 результатов.
     *
     * @param Request $request
     * @param ResultService $resultService
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        ResultService $resultService
    ): JsonResponse {
        try {
            // Получаем топ-10 результатов через сервис
            $topResults = $resultService->getTopResults();

            $response = ['top' => $topResults];

            // Если в запросе есть email, получаем лучший результат этого пользователя
            if ($email = $request->input('email')) {
                $selfResult = $resultService->getUserBestResult($email);
                if ($selfResult) {
                    $response['self'] = $selfResult;
                }
            }

            return ResponseBuilder::success($response);
        } catch (\Exception $e) {
            Log::error('Error fetching top results: ' . $e->getMessage());

            return ResponseBuilder::serverError('An error occurred while fetching the top results.');
        }
    }
}
