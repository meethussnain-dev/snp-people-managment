<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function save($model)
    {
        return $model->save();
    }

    public function insert(array $attributes)
    {
        return $this->model->newQuery()->insert($attributes);
    }

    public function create(array $attributes)
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(array $attributes, int $id)
    {
        $record = $this->findOneOrFail($id);
        $record->update($attributes);

        return $record->fresh();
    }

    public function updateWhere(array $where, array $attributes)
    {
        return $this->model->newQuery()->where($where)->update($attributes);
    }

    public function updateOrCreate(array $search, array $attributes)
    {
        return $this->model->newQuery()->updateOrCreate($search, $attributes);
    }

    public function all(array $columns = ['*'], array $with = [], string $orderBy = 'id', string $direction = 'asc')
    {
        return $this->model->newQuery()->with($with)->orderBy($orderBy, $direction)->get($columns);
    }

    public function allWhere(
        array $columns = ['*'],
        array $where = [],
        array $with = [],
        string $orderBy = 'id',
        string $direction = 'asc'
    ) {
        return $this->model->newQuery()
            ->select($columns)
            ->where($where)
            ->with($with)
            ->orderBy($orderBy, $direction)
            ->get();
    }

    public function find(int $id, array $with = [])
    {
        return $this->model->newQuery()->with($with)->findOrFail($id);
    }

    public function findOneOrFail(int $id)
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    public function firstOrCreate(array $attributes)
    {
        return $this->model->newQuery()->firstOrCreate($attributes);
    }

    public function findBy(array $attributes, array $with = [], string $orderBy = 'id', string $direction = 'asc')
    {
        return $this->model->newQuery()
            ->where($attributes)
            ->with($with)
            ->orderBy($orderBy, $direction)
            ->get();
    }

    public function findOneBy(array $attributes, array $with = [])
    {
        return $this->model->newQuery()->where($attributes)->with($with)->first();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->findOneOrFail($id)->delete();
    }

    public function newModelInstance()
    {
        return $this->model->newInstance();
    }
}
