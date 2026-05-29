<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextareaField extends Component
{
    public $name, $label, $rows, $value, $required;

    public function __construct($name, $label, $rows = 3, $value = '', $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->rows = $rows;
        $this->value = $value;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.textarea-field');
    }
}
