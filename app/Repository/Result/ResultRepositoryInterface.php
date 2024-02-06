<?php

namespace App\Repository\Result;

use App\Models\Result;
use Illuminate\Support\Collection;

interface ResultRepositoryInterface {

    /**
     * Получает топ-N результатов игроков, где N определяется переданным параметром $count.
     * Исключает результаты без связанных участников и возвращает только лучший
     * (наименьший по времени) результат для каждого участника.
     *
     * @param int $count Количество результатов для возврата.
     * @return Collection Коллекция топ-N результатов.
     */
    public function getTopResultsWithMembers(int $count): Collection;

    /**
     * Получает лучший результат для пользователя по его email.
     * Возвращает единственный лучший результат пользователя или null, если таковой отсутствует.
     *
     * @param string $email Email пользователя для поиска его лучшего результата.
     * @return ?Result Может возвращать модель Result или null.
     */
    public function getUserBestResultByEmail(string $email): ?Result;

    /**
     * Определяет место пользователя на основе его лучшего времени в миллисекундах.
     * Подсчитывает, сколько результатов было лучше (меньше миллисекунд), чем у данного.
     *
     * @param int $milliseconds Лучшее время пользователя в миллисекундах.
     * @return int Позиция пользователя среди всех результатов.
     */
    public function getPlaceByMilliseconds(int $milliseconds): int;
}
