<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\RecibosCreateRequest;
use App\Http\Requests\RecibosUpdateRequest;
use App\Repositories\RecibosRepository;
use App\Repositories\UserRepository;
use App\Validators\RecibosValidator;

/**
 * Class RecibosController.
 *
 * @package namespace App\Http\Controllers;
 */
class RecibosController extends Controller
{
    /**
     * @var RecibosRepository
     */
    protected $repository;

    /**
     * @var RecibosValidator
     */
    protected $validator;
    protected $user;

    /**
     * RecibosController constructor.
     *
     * @param RecibosRepository $repository
     * @param RecibosValidator $validator
     */
    public function __construct(RecibosRepository $repository, RecibosValidator $validator, UserRepository $user)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->user  = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(!$user = $this->user->find(1))
        {
            return redirect()->back();
        }
        return $user->recibos()->get();

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $recibos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $recibos,
            ]);
        }
return compact('recibos');
        return view('recibos.index', compact('recibos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RecibosCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(RecibosCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $recibo = $this->repository->create($request->all());

            $response = [
                'message' => 'Recibos created.',
                'data'    => $recibo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recibo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $recibo,
            ]);
        }

        return view('recibos.show', compact('recibo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recibo = $this->repository->find($id);

        return view('recibos.edit', compact('recibo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RecibosUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(RecibosUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $recibo = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Recibos updated.',
                'data'    => $recibo->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Recibos deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Recibos deleted.');
    }
}
