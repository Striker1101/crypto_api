<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FAQ extends Component
{
    /**
     * Create a new component instance.
     */
    public array $datas;

    public function __construct(array $datas)
    {
        //
        $this->datas = $datas;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.f-a-q');
    }
}
