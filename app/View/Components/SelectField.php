<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class SelectField extends Component
{
    public $name;
    public $label;
    public $options; // array or collection
    public $value;   // selected value
    public $placeholder;
    public $required;

    public function __construct($name, $label = '', $options = [], $value = null, $placeholder = null, $required = false)
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst(str_replace('_', ' ', $name));
        $this->options = $options instanceof Collection ? $options : collect($options);
        $this->value = old($name, $value);
        $this->placeholder = $placeholder;
        $this->required = filter_var($required, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('components.select-field');
    }
}
