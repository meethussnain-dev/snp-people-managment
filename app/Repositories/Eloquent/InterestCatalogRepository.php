<?php

namespace App\Repositories\Eloquent;

use App\Models\Interest;
use App\Repositories\Contracts\InterestCatalogRepositoryInterface;

class InterestCatalogRepository extends SnpRepository implements InterestCatalogRepositoryInterface
{
	public function __construct(Interest $interest)
	{
		parent::__construct($interest);
	}
}
