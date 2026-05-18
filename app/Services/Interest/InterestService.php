<?php

namespace App\Services\Interest;

use App\Repositories\Interest\InterestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class InterestService
{
    protected InterestRepositoryInterface $interestRepository;

    public function __construct(InterestRepositoryInterface $interestRepository)
    {
        $this->interestRepository = $interestRepository;
    }

    public function allInterests(): Collection
    {
        return $this->interestRepository->all(['id', 'name'], [], 'name');
    }
}
