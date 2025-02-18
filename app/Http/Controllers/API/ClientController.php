<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Filters\Global\NameFilter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Filters\Global\OrderByFilter;
use App\Http\Requests\API\ClientRequest;
use App\Http\Requests\Global\PageRequest;
use App\Http\Resources\API\ClientResource;
use App\Http\Resources\API\ProjectResource;
use App\Http\Requests\Global\DeleteAllRequest;

class ClientController extends Controller
{
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Client::query())
            // ->through([NameFilter::class, OrderByFilter::class])
            ->thenReturn();
        return successResponse(fetchData($query, $request->pageSize, ClientResource::class));
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());
        return successResponse(new ClientResource($client), __('api.created_success'));
    }

    public function show(Client $client): JsonResponse
    {
        return successResponse(new ClientResource($client));
    }

    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());
        return successResponse(new ClientResource($client), __('api.updated_success'));
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        Client::whereIn('id', $request->ids)->delete();
        return successResponse(msg: __('api.deleted_success'));
    }

}