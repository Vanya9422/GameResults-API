<?php

namespace App\Repository;

use App\Exceptions\Repository\RepositoryException;
use App\Repository\Contracts\CriteriaContract;
use App\Repository\Contracts\RepositoryCriteriaContract;
use App\Repository\Contracts\RepositoryInterface;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Базовый класс репозитория, который определяет основные операции,
 * которые должны быть реализованы в наследуемых репозиториях.
 */
abstract class BaseRepository implements RepositoryInterface, RepositoryCriteriaContract
{
    /**
     * @var Application Контейнер приложения, используется для резолва моделей и других зависимостей.
     */
    protected Application $app;

    /**
     * @var ?Builder|Model Экземпляр модели, с которой работает репозиторий.
     */
    protected Model|Builder|null $model;

    /**
     * @var Collection Коллекция объектов критериев, которые могут быть применены к запросу.
     */
    protected Collection $criteria;

    /**
     * @var bool Флаг, указывающий, следует ли пропускать применение критериев к запросу.
     */
    protected bool $skipCriteria = false;

    /**
     * @param Application $app
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->criteria = new Collection();
        $this->makeModel();
        $this->boot();
    }

    /**
     * Метод для инициализации дополнительной логики после создания репозитория.
     * Может использоваться для добавления начальных критериев фильтрации,
     * которые должны применяться ко всем запросам репозитория.
     */
    public function boot()
    {
        // Добавьте здесь вашу логику инициализации.
        // Например:
        // $this->pushCriteria(new YourInitialCriteria());
    }

    /**
     * Returns the current Model instance
     *
     * @return Model
     */
    public function getModel(): Model {
        return $this->model;
    }

    /**
     * Начинает новый запрос к базе данных, возвращая построитель запросов для модели.
     *
     * @return Builder
     */
    protected function startQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    /**
     * Сбрасывает экземпляр модели, создавая новый экземпляр.
     * @return void
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Возвращает имя класса модели.
     * Этот метод должен быть реализован в каждом конкретном репозитории.
     *
     * @return string Название класса модели.
     */
    abstract protected function getModelClass(): string;

    /**
     * Создает и возвращает экземпляр модели.
     * @return Model
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function makeModel(): Model {
        $model = $this->app->make($this->getModelClass());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->getModelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * Добавляет критерий в коллекцию.
     *
     * @param CriteriaContract $criteria Критерий, который нужно добавить.
     * @return $this
     */
    public function pushCriteria($criteria): static {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Добавляет критерий в коллекцию и сразу же применяет его к запросу.
     * Это позволяет немедленно внести изменения в построение запроса,
     * основываясь на добавленных критериях, что может быть полезно
     * для динамической модификации запроса перед выполнением.
     *
     * @param CriteriaContract $criteria Критерий, который нужно добавить к запросу.
     * @return $this Возвращает текущий экземпляр репозитория для цепочечных вызовов.
     */
    public function pushCriteriaWithApply(CriteriaContract $criteria): static {

        // Добавление критерия в коллекцию
        $this->criteria->push($criteria);

        // Применение всех критериев, включая только что добавленный,
        // к текущему построителю запросов
        $this->applyCriteria();

        // Возвращение текущего экземпляра репозитория
        return $this;
    }

    /**
     * Применяет все критерии к текущему запросу.
     *
     * @return $this
     */
    protected function applyCriteria(): static {
        if ($this->skipCriteria === true) return $this;

        foreach ($this->criteria as $criteria)
            if ($criteria instanceof CriteriaContract)
                $this->model = $criteria->apply($this->model, $this);

        return $this;
    }

    /**
     * Создает новый результат в базе данных.
     *
     * @param array $attributes Атрибуты для создания результата.
     */
    public function create(array $attributes): Model {
        return $this->getModel()->create($attributes);
    }
}
