<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CoreRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SnpRepository implements CoreRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $with = [], string $orderBy = 'id', string $direction = 'asc')
    {
        return $this->model->newQuery()->with($with)->orderBy($orderBy, $direction)->get($columns);
    }

    public function find(int $id, array $with = [])
    {
        return $this->model->newQuery()->with($with)->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(array $attributes, int $id)
    {
        $record = $this->find($id);
        $record->update($attributes);

        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->find($id)->delete();
    }

    public function newQuery()
    {
        return $this->model->newQuery();
    }
}
