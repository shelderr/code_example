<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CategoryIndexRequest;
use App\Services\General\CategoryService;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    private CategoryService $service;

    public function __construct()
    {
        $this->service = resolve(CategoryService::class);
    }

    public function index(CategoryIndexRequest $request)
    {
        $categories = $this->service->index($request->validated()['type']);

        return response(compact('categories'), Response::HTTP_OK);
    }
}
