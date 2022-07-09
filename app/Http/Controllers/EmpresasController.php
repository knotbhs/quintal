<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\EmpresaCreateRequest;
use App\Http\Requests\EmpresaUpdateRequest;
use App\Repositories\EmpresaRepository;
use App\Validators\EmpresaValidator;

/**
 * Class EmpresasController.
 *
 * @package namespace App\Http\Controllers;
 */
class EmpresasController extends Controller
{
    /**
     * @var EmpresaRepository
     */
    protected $repository;

    /**
     * @var EmpresaValidator
     */
    protected $validator;

    /**
     * EmpresasController constructor.
     *
     * @param EmpresaRepository $repository
     * @param EmpresaValidator $validator
     */
    public function __construct(EmpresaRepository $repository, EmpresaValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $empresas = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $empresas,
            ]);
        }

        return view('empresas.index', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EmpresaCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(EmpresaCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $empresa = $this->repository->create($request->all());

            $response = [
                'message' => 'Empresa created.',
                'data'    => $empresa->toArray(),
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
        $empresa = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $empresa,
            ]);
        }

        return view('empresas.show', compact('empresa'));
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
        $empresa = $this->repository->find($id);

        return view('empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EmpresaUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(EmpresaUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $empresa = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Empresa updated.',
                'data'    => $empresa->toArray(),
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
                'message' => 'Empresa deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Empresa deleted.');
    }
}
