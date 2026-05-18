<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class CheckboxMultiSelect extends Component
{
    public string $error;

    public string $wireBinding;

    public function __construct(
        public string $name,
        public string $label,
        public string $wireModel,
        public mixed $options = [],
        public string $optionValue = 'id',
        public string $optionLabel = 'name',
        public string $wireModifier = 'blur',
    ) {
        $errors = session()->get('errors', new ViewErrorBag);

        $this->error = $errors->first($name) ?: $errors->first($name . '.*');
        $this->wireBinding = match ($wireModifier) {
            'live' => 'wire:model.live',
            'blur' => 'wire:model.blur',
            default => 'wire:model',
        };
    }

    public function optionValue(mixed $option): string
    {
        return is_object($option) ? $option->{$this->optionValue} : $option[$this->optionValue];
    }

    public function optionText(mixed $option): string
    {
        return is_object($option) ? $option->{$this->optionLabel} : $option[$this->optionLabel];
    }

    public function render(): View
    {
        return view('components.form.checkbox-multi-select');
    }
}
