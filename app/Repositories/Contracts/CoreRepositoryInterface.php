<?php

namespace App\Repositories\Contracts;

interface CoreRepositoryInterface
{
    public function all(array $columns = ['*'], array $with = [], string $orderBy = 'id', string $direction = 'asc');

    public function find(int $id, array $with = []);

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function delete(int $id): bool;

    public function newQuery();
}
