<?php

namespace App\Services;

use App\Events\PersonCaptured;
use App\Models\Person;
use App\Repositories\PersonRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PersonService
{
    /**
     * @var PersonRepositoryInterface
     */
    protected $people;

    public function __construct(PersonRepositoryInterface $people)
    {
        $this->people = $people;
    }

    public function paginate(int $perPage, ?string $search = null)
    {
        return $this->people->paginatedList($perPage, $search);
    }

    public function findForEdit(int $id): Person
    {
        return $this->people->find($id, ['interests', 'language']);
    }

    public function create(array $attributes): Person
    {
        return DB::transaction(function () use ($attributes) {
            $interestIds = Arr::pull($attributes, 'interests', []);
            $person = $this->people->createProfile($attributes, $interestIds);

            event(new PersonCaptured($person));

            return $person;
        });
    }

    public function update(int $id, array $attributes): Person
    {
        return DB::transaction(function () use ($id, $attributes) {
            $interestIds = Arr::pull($attributes, 'interests', []);
            return $this->people->updateProfile($id, $attributes, $interestIds);
        });
    }

    public function delete(int $id): bool
    {
        return $this->people->delete($id);
    }
}
