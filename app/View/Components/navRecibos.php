<?php

namespace App\View\Components;

use Illuminate\View\Component;

class navRecibos extends Component
{
    public $btnactive;
    public $datastart;
    public $dataend;
    public $texto;
    public $pesquisarpor;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($btnactive, $datastart, $dataend, $texto, $pesquisarpor)
    {
        $this->btnactive = $btnactive;
        $this->datastart = $datastart;
        $this->dataend = $dataend;
        $this->texto = $texto;
        $this->pesquisarpor = $pesquisarpor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav-recibos');
    }
}
