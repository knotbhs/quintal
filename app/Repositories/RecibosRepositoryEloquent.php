<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RecibosRepository;
use App\Entities\Recibos;
use App\Validators\RecibosValidator;

/**
 * Class RecibosRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RecibosRepositoryEloquent extends BaseRepository implements RecibosRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Recibos::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return RecibosValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
