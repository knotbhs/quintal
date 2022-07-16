<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Servicos;

/**
 * Class ServicosTransformer.
 *
 * @package namespace App\Transformers;
 */
class ServicosTransformer extends TransformerAbstract
{
    /**
     * Transform the Servicos entity.
     *
     * @param \App\Entities\Servicos $model
     *
     * @return array
     */
    public function transform(Servicos $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
