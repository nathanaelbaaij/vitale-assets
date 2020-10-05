<?php

namespace App\Http\Controllers;

use App\News;
use App\User;
use Auth;
use App\NewsCategory;

class NewsController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:news-list');
        $this->middleware('permission:news-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:news-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsposts = News::orderBy('created_at', 'desc')->paginate(25);
        $users = User::all();
        return view("news.index", compact('newsposts', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newsCategories = NewsCategory::all();
        return view("news.create", compact('newsCategories'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $user = Auth::user()->id;

        $this->validate(request(), [
            'title' => 'required|min:3|max:255',
            'message' => 'required|min:3|max:255',
            'news_category_id' => 'required|integer',
        ]);

        News::create([
            'title' => request('title'),
            'message' => request('message'),
            'user_id' => $user,
            'news_category_id' => request('news_category_id'),
        ]);

        session()->flash('message', 'Nieuwsbericht is aangemaakt.');
        return redirect('/news');
    }

    /**
     * @param News $newspost
     */
    public function show(News $newspost)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $newspost = News::find($id);
        $newsCategories = NewsCategory::all();
        return view("news.edit", compact('newspost', 'newsCategories'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id)
    {
        $this->validate(request(), [
            'title' => 'required|min:3|max:255',
            'message' => 'required|min:3|max:255',
            'news_category_id' => 'required|integer',
        ]);

        News::where('id', $id)->update([
            'title' => request('title'),
            'message' => request('message'),
            'news_category_id' => request('news_category_id'),
        ]);

        session()->flash('message', 'Nieuwsbericht is gewijzigd.');
        return redirect()->route('news.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $newspost = News::find($id);
        News::where('id', $id)->delete();

        session()->flash('message', 'Nieuwsbericht "' . $newspost->title . '" is verwijderd.');
        return redirect()->route('news.index');
    }
}
