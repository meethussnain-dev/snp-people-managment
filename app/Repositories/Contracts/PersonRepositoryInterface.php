<?php

namespace App\Repositories\Contracts;

interface PersonRepositoryInterface extends CoreRepositoryInterface
{
    public function paginatedList(int $perPage, ?string $search = null);

    public function createProfile(array $attributes, array $interestIds);

    public function updateProfile(int $id, array $attributes, array $interestIds);
}
