<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckboxField extends Component
{
    public string $name;
    public string $label;
    public ?string $id;
    public $checked;
    public $value;

    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $label = '', $checked = null, string $id = null, $value = '1')
    {
        $this->name = $name;
        $this->label = $label;
        $this->checked = $checked;
        $this->id = $id ?? $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.checkbox-field');
    }
}
