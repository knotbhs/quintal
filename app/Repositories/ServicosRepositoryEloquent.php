<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ServicosRepository;
use App\Entities\Servicos;
use App\Validators\ServicosValidator;

/**
 * Class ServicosRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ServicosRepositoryEloquent extends BaseRepository implements ServicosRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Servicos::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ServicosValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
