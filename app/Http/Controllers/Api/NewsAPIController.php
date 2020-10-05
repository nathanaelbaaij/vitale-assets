<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\News;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewspostResource;

class NewsAPIController extends Controller
{
    /**
     * @return NewsResource
     */
    public function index()
    {
        //remove data wrap
        NewsResource::withoutWrapping();
        $newsposts = News::all();
        return new NewsResource($newsposts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param News $newspost
     * @return NewspostResource
     */
    public function show(News $newspost)
    {
        //remove data wrap
        NewsResource::withoutWrapping();
        return new NewspostResource($newspost);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
