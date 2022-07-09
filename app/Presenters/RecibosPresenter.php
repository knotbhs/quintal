<?php

namespace App\Presenters;

use App\Transformers\RecibosTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RecibosPresenter.
 *
 * @package namespace App\Presenters;
 */
class RecibosPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RecibosTransformer();
    }
}
