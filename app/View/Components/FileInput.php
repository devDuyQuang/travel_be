<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileInput extends Component
{
    public ?string $label;
    public string $name;
    public bool $multiple;

    // CÁI BỊ THIẾU:
    public ?string $currentUrl;   // nhận :current-url="..."
    public bool $removable;       // nếu cần checkbox xóa
    public int $previewMaxH;      // nếu cần preview

    // tiện cho view
    public string $inputId;
    public string $removeName;

    public function __construct(
        ?string $label = 'Upload Image',
        string $name = 'file',
        bool $multiple = false,
        ?string $currentUrl = null,   // << thêm
        bool $removable = true,       // << nếu dùng
        int $previewMaxH = 96         // << nếu dùng
    ) {
        $this->label       = $label;
        $this->name        = $name;
        $this->multiple    = $multiple;

        $this->currentUrl  = $currentUrl;   // << quan trọng
        $this->removable   = $removable;
        $this->previewMaxH = $previewMaxH;

        $this->inputId     = $name;
        $this->removeName  = 'remove_' . $name;
    }

    public function render()
    {
        return view('components.file-input');
    }
}
