<?php

namespace App\Http\Controllers\Admin\Management;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Management\Persons\PersonIndexRequest;
use App\Http\Requests\Admin\Management\Persons\VerifyLinkRequest;
use App\Http\Requests\Events\Media\MediaRequest;
use App\Http\Requests\Person\PersonCreateRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Models\Persons;
use App\Services\Base\BaseAppGuards;
use App\Services\Person\PersonService;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    private PersonService $service;

    public function __construct()
    {
        $this->service = resolve(PersonService::class);
        $this->middleware('auth:' . BaseAppGuards::ADMIN);
    }

    /**
     * @param \App\Http\Requests\Admin\Management\Persons\PersonIndexRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PersonIndexRequest $request): Response
    {
        $paginate = $request->validated()['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $persons  = $this->service->getPersons($paginate, null, false);

        return \response(compact('persons'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\PersonCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(PersonCreateRequest $request): Response
    {
        $data   = $request->validated();
        $person = $this->service->create($data);

        return response(compact('person'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\PersonUpdateRequest                   $request
     * @param                                                                 $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(PersonUpdateRequest $request, $id): Response
    {
        $data   = $request->validated();
        $person = $this->service->update($data, $id);

        return response(compact('person'), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id): Response
    {
        $this->service->findOrFail($id)->delete();

        return \response()->noContent();
    }

    public function blockSwitch($id)
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->blockSwitch();

        return \response()->noContent();
    }

    public function verifyUserLink(VerifyLinkRequest $request): Response
    {
        $data = $request->validated();

        $this->service->verifyUserLink($data['user_id'], $data['status']);

        return \response()->noContent();
    }
}
