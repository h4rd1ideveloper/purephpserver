<?php


namespace App\database\repositories;


use App\lib\Helpers;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonRepository
 * @package App\repositories
 */
class PersonRepository implements RepositoryInterface
{
    /**
     *
     */
    const error = [['error' => true]];

    /**
     * @var Model
     */
    private Model $instance;
    /**
     * @var array
     */
    private array $repo;
    /**
     * @var array
     */
    private array $excepted;

    /**
     * PersonRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->setInstance($model);

    }

    /**
     * @param int $skip
     * @param int $limit
     * @return $this
     */
    public function pagination(int $skip, int $limit): PersonRepository
    {
        return $this->setRepo(
            $this
                ->getInstance()
                ->skip($skip)
                ->limit($limit)
                ->get()
                ->toArray() ??
            self::error
        );
    }

    /**
     * @return Model
     */
    public function getInstance(): Model
    {
        return $this->instance;
    }

    /**
     * @param Model $instance
     * @return PersonRepository
     */
    public function setInstance(Model $instance): PersonRepository
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcepted(): array
    {
        return $this->excepted;
    }

    /**
     * @param array $excepted
     * @return PersonRepository
     */
    public function setExcepted(array $excepted): PersonRepository
    {
        $this->excepted = $excepted;
        return $this;
    }

    /**
     * @return array
     */
    public function getRepo(): array
    {

        return $this->repo;
    }

    /**
     * @param array $repo
     * @return PersonRepository
     */
    public function setRepo(array $repo): PersonRepository
    {
        $this->repo = Helpers::Map(
            $repo,
            fn($v) => Helpers::Filter(
                $v,
                fn($value, $key) => $value && !in_array($key, $this->getExcepted())
            )
        );
        return $this;
    }
}