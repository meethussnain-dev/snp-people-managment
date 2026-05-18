<?php

namespace App\Services\Person;

use App\Models\Person;
use App\Repositories\Person\PersonRepositoryInterface;

class PersonService
{
    protected PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function personList(int $perPage, ?string $search = null)
    {
        return $this->personRepository->paginatedList($perPage, $search);
    }

    public function create(array $attributes): Person
    {
        return $this->personRepository->createProfile($attributes);
    }

    public function update(array $attributes, int $id): Person
    {
        return $this->personRepository->updateProfile($id, $attributes);
    }

    public function find(int $id, array $with = []): Person
    {
        return $this->personRepository->find($id, $with);
    }

    public function findOneOrFail(int $id): Person
    {
        return $this->personRepository->findOneOrFail($id);
    }

    public function delete(int $id): bool
    {
        return $this->personRepository->delete($id);
    }
}
