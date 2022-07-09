<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Recibos.
 *
 * @package namespace App\Entities;
 */
class Recibos extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'admin_id',
        'colaborador_id',
        'empresa_id',
        'valor',
        'servico',
        'descricao',
        'data',
        'visible',
        'last_edit',
    ];
    protected $casts = [
        "visible" => 'boolean',
        "data" => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "colaborador_id", "id");
    }

}
