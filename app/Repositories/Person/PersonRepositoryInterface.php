<?php

namespace App\Repositories\Person;

use App\Repositories\BaseRepositoryInterface;

interface PersonRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedList(int $perPage, ?string $search = null);

    public function createProfile(array $attributes);

    public function updateProfile(int $id, array $attributes);
}