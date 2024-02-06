<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ResponseBuilder {
    /**
     * Возвращает ответ об ошибке.
     *
     * @param string $message Сообщение об ошибке.
     * @param int $code HTTP код статуса, по умолчанию 400 (Bad Request).
     * @return JsonResponse
     */
    public static function error(
        string $message,
        int $code = ResponseAlias::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json(['error' => $message], $code);
    }

    /**
     * Возвращает ответ с сообщением о внутренней ошибке сервера.
     *
     * @param string|null $message Опциональное сообщение об ошибке.
     * @param int $code HTTP код статуса, по умолчанию 500 (Internal Server Error).
     * @return JsonResponse
     */
    public static function serverError(
        ?string $message,
        int $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $message = $message ?: 'An internal server error occurred.';
        return response()->json(['error' => $message], $code);
    }

    /**
     * Возвращает успешный ответ с данными.
     *
     * @param mixed $data Данные для включения в ответ.
     * @param string|null $message Опциональное сообщение.
     * @param int $code HTTP код статуса, по умолчанию 200 (OK).
     * @return JsonResponse
     */
    public static function success(
        mixed $data,
        ?string $message = null,
        int $code = ResponseAlias::HTTP_OK
    ): JsonResponse {
        $response = ['data' => $data];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}
