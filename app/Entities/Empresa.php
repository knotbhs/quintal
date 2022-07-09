<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Empresa.
 *
 * @package namespace App\Entities;
 */
class Empresa extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome_fantasia',
        'cnpj',
        'endereco',
        'razao_social', 
        'inscricao_estadual', 
        'email'
    ];
    

}
