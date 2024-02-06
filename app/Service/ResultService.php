<?php

namespace App\Service;

use App\Repository\Member\MemberRepositoryInterface;
use App\Repository\Result\ResultRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ResultService {

    /**
     * @param MemberRepositoryInterface $memberRepository
     * @param ResultRepositoryInterface $resultRepository
     */
    public function __construct(
        protected MemberRepositoryInterface $memberRepository,
        protected ResultRepositoryInterface $resultRepository
    ) { }

    /**
     * Сохраняет результат игры.
     *
     * @param string|null $email Email участника, если предоставлен.
     * @param int $milliseconds Время прохождения игры в миллисекундах.
     * @return Model Результат операции сохранения.
     */
    public function storeResult(?string $email, int $milliseconds): Model {

        $member = null;

        // Поиск или создание участника, если предоставлен email
        if ($email) $member = $this->memberRepository->findByEmailOrCreate($email);

        // Сохранение результата с ссылкой на участника или без неё
        return $this->resultRepository->create([
            'member_id' => $member?->id,
            'milliseconds' => $milliseconds,
        ]);
    }

    /**
     * Получает топ-10 результатов игроков.
     *
     * @return Collection Отформатированная коллекция топ-10 результатов.
     */
    public function getTopResults(): Collection {
        $results = $this->resultRepository->getTopResultsWithMembers(10);

        return $results->map(function ($result, $index) {
            return [
                'email' => $this->maskEmail($result->email), // Маскирование email
                'place' => $index + 1, // Вычисление позиции
                'milliseconds' => $result->milliseconds, // Время прохождения в мс
            ];
        });
    }

    /**
     * Получает лучший результат пользователя по email.
     *
     * @param string $email Email пользователя.
     * @return array|null Массив с информацией о лучшем результате пользователя или null.
     * @throws \Exception
     */
    public function getUserBestResult(string $email): ?array
    {
        try {
            $bestResult = $this->resultRepository->getUserBestResultByEmail($email);

            if (!$bestResult)  return null;

            // Возвращение информации о лучшем результате пользователя
            return [
                'id' => $bestResult->id,
                'email' => $bestResult->email,  // Email пользователя
                'place' => $this->resultRepository->getPlaceByMilliseconds($bestResult->milliseconds), // Позиция
                'milliseconds' => $bestResult->milliseconds, // Время прохождения в мс
            ];
        } catch (\Exception $e) {

            Log::error("Error fetching user's best result: " . $e->getMessage());

            throw $e;
        }
    }

    /**
     * Маскирует email адрес, оставляя только первые две буквы и домен.
     *
     * @param string $email Email адрес для маскирования.
     * @return string Маскированный email адрес.
     */
    private function maskEmail(string $email): string {
        return substr($email, 0, 2) . '******' . strstr($email, '@');
    }
}
