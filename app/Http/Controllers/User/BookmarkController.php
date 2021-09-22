<?php

namespace App\Http\Controllers\User;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Bookmarks\CreateFolderRequest;
use App\Http\Requests\User\Bookmarks\DeleteBookmarkRequest;
use App\Http\Requests\User\Bookmarks\IndexRequest;
use App\Http\Requests\User\Bookmarks\SearchFolderRequest;
use App\Http\Requests\User\Bookmarks\ShowFolderRequest;
use App\Http\Requests\User\Bookmarks\UpdateFolderRequest;
use App\Http\Resources\Events\BookmarkFolderResource;
use App\Services\Event\BookmarkService;
use Illuminate\Http\Response;

class BookmarkController extends Controller
{
    private BookmarkService $service;

    public function __construct()
    {
        $this->service = resolve(BookmarkService::class);
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\IndexRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(IndexRequest $request): Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $paginate = $request->validated()['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $folders = $this->service->foldersIndex($paginate);

        return response(compact('folders'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\SearchFolderRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \ReflectionException
     */
    public function searchFolder(SearchFolderRequest $request)
    {
        $pagination = $request->validated()['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $folders    = $this->service->searchFolder($request->validated()['search'], $pagination);

        return response(compact('folders'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\ShowFolderRequest $request
     * @param                                                     $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function showFolder(ShowFolderRequest $request, $id)
    {
        $foundedFolder = $this->service->showFolder($id);
        $folder        = BookmarkFolderResource::make($foundedFolder);

        return response(compact('folder'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\CreateFolderRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createFolder(CreateFolderRequest $request): Response
    {
        $this->service->createFolder($request->validated()['name']);

        return response()->noContent();
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\UpdateFolderRequest   $request
     * @param                                                         $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function updateFolder(UpdateFolderRequest $request, $id)
    {
        $folder = $this->service->update($request->validated(), $id);

        return \response(compact('folder'), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteFolder($id)
    {
        $this->service->deleteFolder($id);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\DeleteBookmarkRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function deleteBookmark(DeleteBookmarkRequest $request): Response
    {
        $this->service->deleteBookmark($request->validated());

        return \response()->noContent();
    }
}
