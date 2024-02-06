<?php

namespace App\Repository\Criteria;

use App\Repository\Contracts\CriteriaContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PlaceByMillisecondsCriteria implements CriteriaContract
{
    /**
     * @param int $minMilliseconds
     */
    public function __construct(protected int $minMilliseconds) {}

    /**
     * @param Model $model
     * @param $repository
     * @return Builder
     */
    public function apply(Model $model, $repository): Builder {
        return $model
            ->newQuery()
            ->where('milliseconds', '<=', $this->minMilliseconds);
    }
}
