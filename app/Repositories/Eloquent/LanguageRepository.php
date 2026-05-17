<?php

namespace App\Repositories\Eloquent;

use App\Models\Language;
use App\Repositories\LanguageRepositoryInterface;

class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    public function __construct(Language $language)
    {
        parent::__construct($language);
    }
}
