<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Ckeditor extends Component
{
    public $name;
    public $label;
    public $value;

    public function __construct($name, $label = null, $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value ?? old($name);
    }

    public function render()
    {
        return view('components.ckeditor');
    }
}
