<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableBodyDrag extends Component
{
    /**
     * Collection hoặc array of items (mỗi item cần có id và name/title)
     * @var \Illuminate\Support\Collection|array
     */
    public $items;

    /**
     * Route name hoặc url để edit (route name ưu tiên)
     * Nếu truyền null -> sẽ dùng panel_route(module().'.edit', $id)
     */
    public $editRoute;

    /**
     * Route để nhận reorder (POST)
     */
    public $reorderRoute;

    /**
     * Id attribute cho UL (mặc định handle-list-1)
     */
    public $listId;

    public function __construct($items = [], $editRoute = null, $reorderRoute = null, $listId = 'handle-list-1')
    {
        $this->items = $items;
        $this->editRoute = $editRoute;
        $this->listId = $listId;
        $this->reorderRoute = $reorderRoute ?? panel_route(module().'.reorder');
    }

    public function render()
    {
        return view('components.table-body-drag');
    }
}
