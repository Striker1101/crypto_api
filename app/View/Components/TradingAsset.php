<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TradingAsset extends Component
{
    public array $datas;

    /**
     * Create a new component instance.
     */
    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.trading-asset');
    }
}
