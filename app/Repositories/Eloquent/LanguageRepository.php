<?php

namespace App\Repositories\Eloquent;

use App\Models\Language;
use App\Repositories\Contracts\LanguageRepositoryInterface;

class LanguageRepository extends SnpRepository implements LanguageRepositoryInterface
{
	public function __construct(Language $language)
	{
		parent::__construct($language);
	}
}
