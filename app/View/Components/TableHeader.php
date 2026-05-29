<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableHeader extends Component
{
    public string $title;
    public ?string $createRoute;
    public bool $showUpdateOrder;
    public string $modalId;
    public bool $useDefaultModal;
    public string $modalTitle;
    public string $modalBody;
    public string $confirmText;
    public string $cancelText;
    public string $confirmEvent;

    public function __construct(
        string $title = '',
        ?string $createRoute = null,
        $showUpdateOrder = false,          // ← accept mixed
        string $modalId = 'updateOrderModal',
        $useDefaultModal = true,
        string $modalTitle = 'Cập nhật Thứ Tự',
        string $modalBody = 'Xác nhận lưu lại thứ tự hiện tại?',
        string $confirmText = 'Lưu thứ tự',
        string $cancelText = 'Hủy',
        string $confirmEvent = 'update-order:confirm',
    ) {
        $this->title = $title;
        $this->createRoute = $createRoute;
        // Map kebab-case prop "show-update-order" → bool
        $this->showUpdateOrder = filter_var($showUpdateOrder, FILTER_VALIDATE_BOOLEAN);
        $this->modalId = $modalId;
        $this->useDefaultModal = filter_var($useDefaultModal, FILTER_VALIDATE_BOOLEAN);
        $this->modalTitle = $modalTitle;
        $this->modalBody = $modalBody;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->confirmEvent = $confirmEvent;
    }

    public function render()
    {
        return view('components.table-header');
    }
}
