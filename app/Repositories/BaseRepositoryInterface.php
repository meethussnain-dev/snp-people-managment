<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function save($model);

    public function insert(array $attributes);

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function updateWhere(array $where, array $attributes);

    public function updateOrCreate(array $search, array $attributes);

    public function all(array $columns = ['*'], array $with = [], string $orderBy = 'id', string $direction = 'asc');

    public function allWhere(
        array $columns = ['*'],
        array $where = [],
        array $with = [],
        string $orderBy = 'id',
        string $direction = 'asc'
    );

    public function find(int $id, array $with = []);

    public function findOneOrFail(int $id);

    public function firstOrCreate(array $attributes);

    public function findBy(array $attributes, array $with = [], string $orderBy = 'id', string $direction = 'asc');

    public function findOneBy(array $attributes, array $with = []);

    public function delete(int $id): bool;

    public function newModelInstance();
}
