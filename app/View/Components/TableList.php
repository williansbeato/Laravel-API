<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableList extends Component
{

    public $header;
    public $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($header,$data)
    {
        $this-> header = $header;

        $this-> data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.tablelist');
    }
}
