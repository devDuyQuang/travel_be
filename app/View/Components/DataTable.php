<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class DataTable extends Component
{
    public string $id;
    public array $columns;
    public ?array $data;
    public ?string $ajaxUrl;
    public array $options;

    public function __construct(
        ?string $id = null,
        array $columns = [],
        ?array $data = null,         // client-side
        ?string $ajaxUrl = null,     // server-side
        array $options = []          // tuỳ chọn DataTables
    ) {
        $this->id = $id ?: 'dt_' . Str::uuid()->toString();
        $this->columns = $columns;
        $this->data = $data;
        $this->ajaxUrl = $ajaxUrl;
        $this->options = $options;
    }

    public function render()
    {
        return view('components.data-table');
    }
}
