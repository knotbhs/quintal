<?php

namespace App\Presenters;

use App\Transformers\ServicosTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ServicosPresenter.
 *
 * @package namespace App\Presenters;
 */
class ServicosPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ServicosTransformer();
    }
}
