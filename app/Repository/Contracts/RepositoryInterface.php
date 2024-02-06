<?php

namespace App\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Общий интерфейс для всех репозиториев
 */
interface RepositoryInterface {

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;
}
