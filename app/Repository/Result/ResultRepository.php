<?php

namespace App\Repository\Result;

use App\Models\Result;
use App\Repository\BaseRepository;
use App\Repository\Criteria\PlaceByMillisecondsCriteria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResultRepository extends BaseRepository implements ResultRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return \App\Models\Result::class;
    }

    /**
     * Получает топ-N результатов игроков, где N определяется переданным параметром $count.
     * Исключает результаты без связанных участников и возвращает только лучший
     * (наименьший по времени) результат для каждого участника.
     *
     * @param int $count Количество результатов для возврата.
     * @return Collection Коллекция топ-N результатов.
     */
    public function getTopResultsWithMembers(int $count): Collection {
        return $this->startQuery()->select('email', DB::raw('MIN(milliseconds) as milliseconds'))
            ->join('members', 'members.id', '=', 'results.member_id')
            ->groupBy('members.email')
            ->orderBy('milliseconds')
            ->take($count)
            ->get();
    }

    /**
     * Получает лучший результат по времени для пользователя по указанному email.
     * Возвращает первый найденный результат, который является лучшим для этого пользователя.
     *
     * @param string $email Email пользователя для поиска его лучшего результата.
     * @return ?Result Объект результата или null, если результат не найден.
     */
    public function getUserBestResultByEmail(string $email): ?Result {
        return $this->startQuery()->whereHas('member', function ($query) use ($email) {
            $query->where('email', $email);
        })
            ->with('member')
            ->orderBy('milliseconds')
            ->first();
    }

    /**
     * Получает позицию пользователя по времени прохождения.
     *
     * @param int $milliseconds
     * @return int
     */
    public function getPlaceByMilliseconds(int $milliseconds): int {

        $criteria = new PlaceByMillisecondsCriteria($milliseconds);

        $this->pushCriteriaWithApply($criteria);

        return $this->model->count();
    }
}
