<?php

namespace App\database\repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{

    /**
     * PersonRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model);

    /**
     * @return array
     */
    public function getRepo(): array;

    /**
     * @param array $repo
     * @return PersonRepository
     */
    public function setRepo(array $repo): PersonRepository;

    /**
     * @return Model
     */
    public function getInstance(): Model;

    /**
     * @param Model $model
     * @return PersonRepository
     */
    public function setInstance(Model $model): PersonRepository;
}