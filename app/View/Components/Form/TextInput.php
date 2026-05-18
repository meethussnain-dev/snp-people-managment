<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class TextInput extends Component
{
    public string $error;

    public string $inputClasses;

    public string $wireBinding;

    public function __construct(
        public string $name,
        public string $label,
        public string $wireModel,
        public string $type = 'text',
        public string $placeholder = '',
        public ?string $icon = null,
        public ?string $maxlength = null,
        public string $wireModifier = 'blur',
    ) {
        $errors = session()->get('errors', new ViewErrorBag);

        $this->error = $errors->first($name);
        $this->inputClasses = 'form-control' . ($icon ? ' border-start-0 ps-0' : '') . ($this->error ? ' is-invalid' : '');
        $this->wireBinding = match ($wireModifier) {
            'live' => 'wire:model.live',
            'blur' => 'wire:model.blur',
            default => 'wire:model',
        };
    }

    public function render(): View
    {
        return view('components.form.text-input');
    }
}
