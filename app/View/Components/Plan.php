<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Plan extends Component
{
    /**
     * The plans data.
     *
     * @var mixed
     */
    public $plans;
    public array $datas;

    /**
     * Create a new component instance.
     *
     * @param mixed $plans
     */
    public function __construct($plans, array $datas)
    {
        $this->plans = $plans;  // Correctly assign the passed value to the plans property
        $this->datas = $datas;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.plan');
    }
}
