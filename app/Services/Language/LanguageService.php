<?php

namespace App\Services\Language;

use App\Repositories\Language\LanguageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LanguageService
{
    protected LanguageRepositoryInterface $languageRepository;

    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function allLanguages(): Collection
    {
        return $this->languageRepository->all(['id', 'name'], [], 'name');
    }
}
