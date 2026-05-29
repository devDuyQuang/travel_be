<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionDropdown extends Component
{
    public $editUrl;
    public $editRoute;
    public $id;
    public $showEdit;
    public $showDelete;
    public $deleteModalTarget;
    public $editLabel;
    public $deleteLabel;

    /**
     * @param string|null $editUrl     - dùng trực tiếp URL
     * @param string|null $editRoute   - tên route (route name) để gọi route($editRoute, $id)
     * @param int|string|null $id      - id để build route nếu dùng editRoute
     * @param bool $showEdit
     * @param bool $showDelete
     * @param string $deleteModalTarget - modal selector (ví dụ '#deleteModal')
     * @param string $editLabel
     * @param string $deleteLabel
     */
    public function __construct(
        $editUrl = null,
        $editRoute = null,
        $id = null,
        $showEdit = true,
        $showDelete = true,
        $deleteModalTarget = '#deleteModal',
        $editLabel = 'Chỉnh Sửa',
        $deleteLabel = 'Xóa'
    ) {
        $this->editUrl = $editUrl;
        $this->editRoute = $editRoute;
        $this->id = $id;
        $this->showEdit = filter_var($showEdit, FILTER_VALIDATE_BOOLEAN);
        $this->showDelete = filter_var($showDelete, FILTER_VALIDATE_BOOLEAN);
        $this->deleteModalTarget = $deleteModalTarget;
        $this->editLabel = $editLabel;
        $this->deleteLabel = $deleteLabel;
    }

    /**
     * Compute editUrl if editRoute is provided.
     */
    public function computedEditUrl()
    {
        // priority: explicit editUrl > editRoute + id > #
        if (!empty($this->editUrl)) {
            return $this->editUrl;
        }

        if (!empty($this->editRoute) && !empty($this->id)) {
            try {
                return route($this->editRoute, $this->id);
            } catch (\Throwable $e) {
                // fallback to placeholder
            }
        }

        return '#';
    }

    public function render()
    {
        return view('components.action-dropdown');
    }
}
