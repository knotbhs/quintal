<?php

namespace App\Presenters;

use App\Transformers\EmpresaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EmpresaPresenter.
 *
 * @package namespace App\Presenters;
 */
class EmpresaPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EmpresaTransformer();
    }
}
