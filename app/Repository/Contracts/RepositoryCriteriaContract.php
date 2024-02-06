<?php

namespace App\Repository\Contracts;

/**
 * Interface RepositoryCriteriaInterface
 * Определяет методы для управления критериями в репозитории.
 */
interface RepositoryCriteriaContract {

    /**
     * Добавляет критерий в репозиторий.
     *
     * @param CriteriaContract $criteria Критерий для добавления.
     * @return static
     */
    public function pushCriteria(CriteriaContract $criteria): static;

    /**
     * Добавляет критерий в коллекцию и сразу же применяет его к запросу
     *
     * @param CriteriaContract $criteria Критерий для добавления.
     * @return static
     */
    public function pushCriteriaWithApply(CriteriaContract $criteria): static;
}
