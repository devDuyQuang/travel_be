<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SubmitButtons extends Component
{
    public $cancelRoute;
    public $submitText;
    public $cancelText;

    public function __construct($cancelRoute = '#', $submitText = 'Lưu', $cancelText = 'Hủy')
    {
        $this->cancelRoute = $cancelRoute;
        $this->submitText = $submitText;
        $this->cancelText = $cancelText;
    }

    public function render()
    {
        return view('components.submit-buttons');
    }
}
