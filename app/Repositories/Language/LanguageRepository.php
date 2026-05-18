<?php

namespace App\Repositories\Language;

use App\Models\Language;
use App\Repositories\Eloquent\BaseRepository;

class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    public function __construct(Language $language)
    {
        parent::__construct($language);
    }
}