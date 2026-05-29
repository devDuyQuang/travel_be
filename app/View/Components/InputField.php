<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputField extends Component
{
    public $name, $label, $type, $value, $required;

    public function __construct($name, $label, $type = 'text', $value = '', $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.input-field');
    }
}
