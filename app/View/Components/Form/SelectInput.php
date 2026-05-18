<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class SelectInput extends Component
{
    public string $error;

    public string $wireBinding;

    public function __construct(
        public string $name,
        public string $label,
        public string $wireModel,
        public mixed $options = [],
        public ?string $placeholder = null,
        public string $optionValue = 'id',
        public string $optionLabel = 'name',
        public string $wireModifier = 'blur',
    ) {
        $errors = session()->get('errors', new ViewErrorBag);

        $this->error = $errors->first($name);
        $this->wireBinding = match ($wireModifier) {
            'live' => 'wire:model.live',
            'blur' => 'wire:model.blur',
            default => 'wire:model',
        };
    }

    public function optionValue(mixed $option, int|string $key): string
    {
        if (is_object($option)) {
            return $option->{$this->optionValue};
        }

        return is_array($option) ? $option[$this->optionValue] : $key;
    }

    public function optionText(mixed $option): string
    {
        if (is_object($option)) {
            return $option->{$this->optionLabel};
        }

        return is_array($option) ? $option[$this->optionLabel] : $option;
    }

    public function render(): View
    {
        return view('components.form.select-input');
    }
}
