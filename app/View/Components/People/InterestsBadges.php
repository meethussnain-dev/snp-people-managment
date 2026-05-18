<?php

namespace App\View\Components\People;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class InterestsBadges extends Component
{
    public Collection $visible;

    public int $overflow;

    public string $allNames;

    public function __construct(
        public Collection $interests,
    ) {
        $this->visible = $interests->take(2);
        $this->overflow = $interests->count() - $this->visible->count();
        $this->allNames = $interests->pluck('name')->implode(', ');
    }

    public function render(): View
    {
        return view('components.people.interests-badges');
    }
}
