<?php

namespace App\Services;

use App\Repositories\Contracts\InterestCatalogRepositoryInterface;

class InterestCatalogService
{
    /**
     * @var InterestCatalogRepositoryInterface
     */
    protected $interestRepository;

    public function __construct(InterestCatalogRepositoryInterface $interestRepository)
    {
        $this->interestRepository = $interestRepository;
    }

    public function allInterests()
    {
        return $this->interestRepository->all(['id', 'name'], [], 'name');
    }
}
