<?php

namespace App\Repositories;

interface PersonRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedList(int $perPage, ?string $search = null);

    public function createProfile(array $attributes, array $interestIds);

    public function updateProfile(int $id, array $attributes, array $interestIds);
}
