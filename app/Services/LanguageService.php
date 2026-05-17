<?php

namespace App\Services;

use App\Repositories\LanguageRepositoryInterface;

class LanguageService
{
    /**
     * @var LanguageRepositoryInterface
     */
    protected $languageRepository;

    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function allLanguages()
    {
        return $this->languageRepository->all(['id', 'name'], [], 'name');
    }
}
