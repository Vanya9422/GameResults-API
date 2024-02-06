<?php

namespace App\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;

interface CriteriaContract {

    /**
     * Применяет критерий к запросу Eloquent.
     *
     * @param Model $model Базовый запрос, к которому применяется критерий.
     * @param RepositoryInterface $repository Репозиторий, в контексте которого используется критерий.
     */
    public function apply(Model $model, RepositoryInterface $repository);
}
