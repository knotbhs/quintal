<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Recibos;

/**
 * Class RecibosTransformer.
 *
 * @package namespace App\Transformers;
 */
class RecibosTransformer extends TransformerAbstract
{
    /**
     * Transform the Recibos entity.
     *
     * @param \App\Entities\Recibos $model
     *
     * @return array
     */
    public function transform(Recibos $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
