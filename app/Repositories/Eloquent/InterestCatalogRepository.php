<?php

namespace App\Repositories\Eloquent;

use App\Models\Interest;
use App\Repositories\InterestCatalogRepositoryInterface;

class InterestCatalogRepository extends BaseRepository implements InterestCatalogRepositoryInterface
{
    public function __construct(Interest $interest)
    {
        parent::__construct($interest);
    }
}
