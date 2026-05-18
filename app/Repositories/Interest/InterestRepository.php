<?php

namespace App\Repositories\Interest;

use App\Models\Interest;
use App\Repositories\Eloquent\BaseRepository;

class InterestRepository extends BaseRepository implements InterestRepositoryInterface
{
    public function __construct(Interest $interest)
    {
        parent::__construct($interest);
    }
}
