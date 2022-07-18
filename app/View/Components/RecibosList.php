<?php

namespace App\View\Components;

use App\Http\Controllers\RecibosController;
use Illuminate\View\Component;

class RecibosList extends Component
{
    public $recibo;
    public $reciboController;
    public $i;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($recibo, $i, RecibosController $reciboController)
    {
        $this->recibo = $recibo;
        $this->i = $i;
        $this->reciboController = $reciboController;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataExplode = explode("-", explode(" ", $this->recibo->data)[0]);
        $this->recibo->valor = "R$ " . number_format($this->recibo->valor, 2, ",", ".");
        $this->recibo->data = $this->reciboController->dataExtenso(date("Y/m/d", strtotime($dataExplode[2] . "-" . $dataExplode[1] . "-" . $dataExplode[0])));
        return view('components.recibos-list');
    }
}
