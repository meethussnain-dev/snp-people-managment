<?php

namespace App\Repositories\Eloquent;

use App\Models\Person;
use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonRepository extends SnpRepository implements PersonRepositoryInterface
{
    public function paginatedList(int $perPage, ?string $search = null)
    {
        return $this->newQuery()
            ->with(['language', 'interests', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', '%' . $search . '%')
                        ->orWhere('surname', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('sa_id_number', 'like', '%' . $search . '%')
                        ->orWhere('mobile_number', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function createProfile(array $attributes, array $interestIds)
    {
        /** @var Person $person */
        $person = $this->create($attributes);
        $person->interests()->sync($interestIds);

        return $person->load(['language', 'interests', 'creator']);
    }

    public function updateProfile(int $id, array $attributes, array $interestIds)
    {
        /** @var Person $person */
        $person = $this->update($attributes, $id);
        $person->interests()->sync($interestIds);

        return $person->load(['language', 'interests', 'creator']);
    }
}
