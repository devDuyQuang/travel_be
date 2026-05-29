<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class TabForm extends Component
{
    public string $action;
    public string $method;
    public string $enctype;
    public string $formClass;
    public string $indexUrl;
    public bool   $remember;

    /** @var array<int, array{id:string,title:string,active:bool,slot?:string}> */
    public array $tabs;

    /**
     * @param array<int, array{id?:string,title:string,active?:bool,slot?:string}> $tabs
     */
    public function __construct(
        string $action,
        string $method = 'POST',
        string $enctype = 'multipart/form-data',
        array $tabs = [],
        string $formClass = 'ajax-form',
        ?string $indexUrl = null,
        bool $remember = true
    ) {
        $this->action    = $action;
        $this->method    = strtoupper($method);
        $this->enctype   = $enctype;
        $this->formClass = $formClass;
        $this->indexUrl  = $indexUrl ?? '';
        $this->remember  = $remember;

        // Chuẩn hoá tabs: id/active/slot
        $prepared  = [];
        $anyActive = false;

        foreach ($tabs as $tab) {
            $id     = $tab['id'] ?? Str::slug($tab['title']);
            $active = (bool)($tab['active'] ?? false);
            $slot   = $tab['slot'] ?? null;

            $anyActive = $anyActive || $active;
            $prepared[] = [
                'id'     => $id,
                'title'  => $tab['title'],
                'active' => $active,
                'slot'   => $slot,
            ];
        }

        if (!$anyActive && $prepared) {
            $prepared[0]['active'] = true;
        }

        $this->tabs = $prepared;
    }

    public function render()
    {
        return view('components.tab-form');
    }
}
