<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Category;
use DB;

/**
 * Class CategoriesController
 * @package App\Http\Controllers
 * @author Nathanael Baaij
 */
class CategoriesController extends Controller
{
    public $glyphs = [
        "e900" => "Afsluiters",
        "e901" => "Afvoergemaak",
        "e902" => "Ambulance post",
        "e903" => "Apotheek",
        "e904" => "Brandweer post",
        "e905" => "Verpleeg",
        "e906" => "Vraag",
        "e90a" => "C2000",
        "e90b" => "Drinkwater",
        "e90c" => "Gasmeetenregelstation",
        "e90d" => "Gasontvangststation",
        "e90e" => "Gem rioolgemaal",
        "e90f" => "Gsm mast",
        "e910" => "Hoogspanning hoofdverdeelstation",
        "e911" => "Huisarts",
        "e912" => "Laagspanning",
        "e913" => "Landbouwwaterinnamepunt",
        "e914" => "LTE mast",
        "e915" => "Middenspanning distributiestation",
        "e916" => "Middenspanning onderstation",
        "e917" => "Middenspanning schakelstation",
        "e918" => "Middenspanning verdeelstation",
        "e919" => "Politie post",
        "e91a" => "Pompstation",
        "e91b" => "Proceswater productielocatie",
        "e91c" => "Radio beacon",
        "e91d" => "Rioolgemaal",
        "e91e" => "Rwzi",
        "e926" => "Straatkast",
        "e927" => "Aanvoergemalen",
        "e928" => "Tandartsen post",
        "e929" => "Umts",
        "e92b" => "Wijkcentrale"
    ];
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:asset-category-list');
        $this->middleware('permission:asset-category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset-category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset-category-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Get all the main categories to the categories.index page
     * wcalith sortable options and pagination of maximum 20 per page
     * index also checks and acts on filter options
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::with('assets')->whereNull('parent_id')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * show the relevant category by id with optional parent category and optional children category
     * the children are listed in a table that is sortable
     * return a show view with the given data
     * @param $id category id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $category = Category::with('children')->with('parent')->find($id);
        $assets = Asset::all()->where('category_id', $id);

        return view('categories.show', [
            'category' => $category,
            'assets' => $assets
        ]);
    }

    /**
     * return the category create view with all the categories from the database sorted on asc order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::getAllCategories('asc')->get();
        return view('categories.create', compact('categories'))->with('symbols', $this->glyphs);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:255',
            'description' => 'max:1024',
            'threshold' => 'nullable|numeric',
            'symbol' => 'required'
        ]);

        Category::create([
            'name' => request('name'),
            'description' => request('description'),
            'threshold' => (double)request('threshold'),
            'parent_id' => request('parent_id'),
            'symbol' => request('symbol'),
        ]);

        session()->flash('message', 'Asset categorie "' . request('name') . '" is aangemaakt.');
        return redirect()->route('categories.index');
    }

    /**
     * Find the relevant category by id with all the categories in the database ordered by asc except the current one
     * return a edit view with the data
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $parentCategories = Category::exceptGivenId($id)->orderBy('name', 'asc')->get();

        return view('categories.edit', [
            'symbols' => $this->glyphs, 
            'category' => $category,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => [
                'required',
                'min:3',
                'max:255',
            ],
            'description' => 'max:1024',
            'threshold' => 'nullable|numeric',
            'symbol' => 'required'
        ]);

        $category = Category::find($id);

        $category->update([
            'name' => request('name'),
            'description' => request('description'),
            'threshold' => (double)request('threshold'),
            'parent_id' => (request('parent_id') == '' ? null : request('parent_id')),
            'symbol' => request('symbol'),
        ]);

        session()->flash('message', 'Asset categorie "' . request('name') . '" is gewijzigd.');
        return redirect()->route('categories.index');
    }

    /**
     * Checks if category exists if so it gets deleted and
     * the user will be redirected back to the category.index
     * with a success flash message
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $categoryName = $category->name;

        $category->delete();

        session()->flash('message', 'Asset categorie "' . $categoryName . '" is verwijderd.');
        return redirect()->route('categories.index');
    }

    /**
     * this method searches categories in the database and returns json
     * if it can't find what the user wants it returns all the categories.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        //Trims the search request
        $searchTerm = trim(preg_replace('/\s/', '', $request->search));
        //Check if the search request is ajax call and isn't whitespaces of spaces
        if ($request->ajax() && $searchTerm !== '') {
            $categories = DB::table('categories')
                //->whereNull('parent_id')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
                })
                ->paginate(20);
        } else {
            //If it cant find anything it will return all the categories
            $categories = DB::table('categories')->paginate(20);
        }

        //return result in json for ajaxcall
        return response()->json($categories);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getThresholdByAssetId(Request $request)
    {
        $categoryId = $request->categoryId;
        $category = Category::find($categoryId);
        return $category;
    }
}
