<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Empresa;

/**
 * Class EmpresaTransformer.
 *
 * @package namespace App\Transformers;
 */
class EmpresaTransformer extends TransformerAbstract
{
    /**
     * Transform the Empresa entity.
     *
     * @param \App\Entities\Empresa $model
     *
     * @return array
     */
    public function transform(Empresa $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
