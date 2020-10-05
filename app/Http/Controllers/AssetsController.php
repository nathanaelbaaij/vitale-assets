<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Category;
use App\PropertyName;
use App\AssetProperty;
use App\Logbook;
use App\LogbookReverted;
use App\AssetPic;
use App\Comment;
use App\CommentPic;
use App\LoadLevel;
use App\Cascade;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use DB;

/**
 * Class AssetsController
 * @package App\Http\Controllers
 * @author Pepijn
 */
class AssetsController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:asset-list');
        $this->middleware('permission:asset-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $assets = Asset::with('category')->get();
        return view('assets.index', compact('assets'));
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $asset = Asset::where('id', $id)->with('category')->first();
        $assetPic = AssetPic::all()->where('asset_id', $id)->first();
        $loadLevels = LoadLevel::all();
        $comments = Comment::where('asset_id', $id)->orderBy('created_at', 'desc')->get();
        $commentPic = CommentPic::all();
        $cascades = Cascade::with('assetFrom')->with('assetTo')->with('consequence')->where('asset_id_from', $id)->orWhere('asset_id_to', $id)->get();

        $log = Logbook::select(
            'logbooks.id as lb_id',
            'logbooks.asset_id as lb_ass_id',
            'us.name as username',
            'av.image_url',
            'logbooks.json_data as lb_json_data',
            'as.name'
        )
            ->where("logbooks.asset_id", "=", $id)
            ->join('assets as as', 'as.id', '=', 'logbooks.asset_id')
            ->where('av.active', '=', '1')
            ->join('users as us', 'us.id', '=', 'logbooks.user_id')
            ->join('avatars as av', 'av.user_id', '=', 'logbooks.user_id')
            ->orderBy('logbooks.updated_at', 'DESC')
            ->get();

        return view('assets.show', [
            'asset' => $asset,
            'assetPic' => $assetPic,
            'loadLevels' => $loadLevels,
            'logs' => $log,
            'comments' => $comments,
            'commentPic' => $commentPic,
            'cascades' => $cascades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::whereNotNull('threshold')->get();
        return view('assets.create', compact("categories"));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:100',
            'description' => 'max:1024',
            'category' => 'required',
            'xCoordinate' => 'required',
            'yCoordinate' => 'required',
            'thresholdCorrection' => 'required|numeric',
            'assetPicture' => 'bail|mimes:jpeg,jpg,png',
            'threshold_correction' => 'numeric|nullable'
        ]);

        $asset = Asset::create([
            'name' => request('name'),
            'description' => request('description'),
            'category_id' => request('category'),
            'x_coordinate' => doubleval(request('xCoordinate')),
            'y_coordinate' => doubleval(request('yCoordinate')),
            'threshold_correction' => request('thresholdCorrection'),
        ]);

        //Upload the asset picture
        $this->storePicture(request('assetPicture'), $asset->id);

        $this->createProperty($asset, request()->property);

        session()->flash('message', 'Asset "' . request('name') . '"is aangemaakt.');
        return redirect(request('url'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $asset = Asset::where('id', $id)->with('category')->first();
        $categories = Category::whereNotNull('threshold')->get();
        $assetPic = AssetPic::all()->where('asset_id', $id)->first();

        return view('assets.edit', [
            'asset' => $asset,
            'categories' => $categories,
            'assetPic' => $assetPic,
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
            'name' => 'required|min:3|max:100',
            'description' => 'max:1024',
            'category' => 'required',
            'xCoordinate' => 'required',
            'yCoordinate' => 'required',
            'threshold_correction' => 'numeric|nullable',
            'thresholdCorrection' => 'required|numeric',
            'assetPicture' => 'bail|mimes:jpeg,jpg,png'
        ]);

        //get the user id from current loggedin user
        $userId = Auth::user()->id;

        //find current asset
        $asset = Asset::find($id);

        //make a old logbook
        $this->createOldLogbookModel($asset, $userId);

        $asset->update([
            'name' => request('name'),
            'description' => request('description'),
            'category_id' => request('category'),
            'x_coordinate' => doubleval(request('xCoordinate')),
            'y_coordinate' => doubleval(request('yCoordinate')),
            'threshold_correction' => request('thresholdCorrection')
        ]);

        //make a new logbook
        $this->createNewLogbookModel($asset, $userId);

        //update asset picture
        $this->updatePicture($asset, request());

        //update property
        $this->updateProperty($asset, request()->property);

        session()->flash('message', 'Asset "' . request('name') . '" is gewijzigd.');
        return redirect(request('url'));
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @param  \App\Asset $id
     */
    public function destroy($id)
    {
        //finds the asset associating to the id
        $assetResult = Asset::findOrFail($id);

        //delete asset properties
        $property = AssetProperty::where('asset_id', '=', $assetResult->id);
        $property->delete();

        //delete the asset logbooks
        $this->destroyLogbook($assetResult->id);

        //delete the asset picture
        $this->destroyPicture($assetResult->id);

        //delete the asset
        $assetResult->delete();

        session()->flash('message', 'Asset "' . $assetResult->name . '" is verwijderd.');
        return redirect()->route('assets.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addComment($id)
    {
        $this->validate(request(), [
            'message' => 'required|min:3|max:255',
            'file' => 'bail|mimes:jpeg,jpg,png'
        ]);

        $comment = Comment::create([
            'asset_id' => $id,
            'user_id' => auth()->user()->id,
            'message' => request('message'),
        ]);

        if (request('imageFile')) {
            $file = request('imageFile');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/comments', $filename);
            CommentPic::create([
                'comment_id' => $comment->id,
                'image_url' => $filename,
            ]);
        }

        session()->flash('message', 'Opmerking is geplaatst.');
        return redirect('/assets/' . $id);
    }

    /**
     * This method looks up the breachlocations with the corresponding waterdepths of a asset
     * @param Request $request with an asset id and loadlevel id
     * @return \Illuminate\Http\JsonResponse a json respons filled with breachlocations and waterdepths
     */
    public function assetFloatScenarios(Request $request)
    {
        //define asset id and the loadlevel id
        $assetId = $request->assetId;
        $loadLevelId = $request->loadLevelId;

        $asset = Asset::find($assetId);

        //check if something is found if not return an empty json response
        if (!$asset) {
            return response()->json([
                'data' => ''
            ]);
        }

        //create a json response
        $resultsArray = [];
        foreach ($asset->depths()->where('load_level_id', '=', $request->loadLevelId)->get() as $depth) {
            //filter out the results with the loadlevel, so only show the data with the requested loadlevel
            //create results array
            $resultsArray[] = [
                $depth->breachLocation->name, //breachlocation name
                $depth->water_depth, //corresponding waterdepth
                $asset->computeAssetState($depth->breachLocation->id, $loadLevelId), // get asset state
            ];
        }

        //return the json results
        return response()->json([
            'data' => $resultsArray
        ]);
    }

    /**
     * this method adds a new picture by a asset
     * @param $requestPicture
     * @param $assetId
     */
    public function storePicture($requestPicture, $assetId)
    {
        //check if the user has added a image to the form upload
        if ($requestPicture) {
            $file = $requestPicture;
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/assets', $filename);

            //save to the database
            AssetPic::create([
                'asset_id' => $assetId,
                'image' => $filename,
            ]);
        }
    }

    /**
     * this method updates the asset picture
     * @param $asset
     * @param $request
     */
    public function updatePicture($asset, $request)
    {
        $image = AssetPic::where('asset_id', $asset->id)->first();
        //check if there is an image uploaded or still there
        if ($request->check === "true") {
            //check if there is an upload
            if ($request->assetPicture) {
                //check if there is an image in database
                if ($image) {
                    $existingFile = $image->image;
                    unlink("uploads/assets/" . $existingFile);
                    $file = $request->assetPicture;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/assets', $filename);
                    AssetPic::all()->where('asset_id', $asset->id)->first()->update([
                        'asset_id' => $asset->id,
                        'image' => $filename,
                    ]);
                } else {
                    $file = $request->assetPicture;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/assets', $filename);
                    AssetPic::create([
                        'asset_id' => $asset->id,
                        'image' => $filename,
                    ]);
                }
            }
            //delete image if there is none
        } else {
            unlink("uploads/assets/" . $image->image);
            $image->delete();
        }
    }

    /**
     * this method deletes the picture in the database and storage
     * @param $assetId
     */
    public function destroyPicture($assetId)
    {
        $assetPic = AssetPic::find($assetId);
        if ($assetPic) {
            unlink("uploads/assets/" . $assetPic->image);
            $assetPic->delete();
        }
    }

    /**
     * @param $asset
     * @param $user_id
     */
    private function createOldLogbookModel($asset, $user_id)
    {
        $asset_json = json_encode($asset, true);
        $log = new Logbook;
        $log->asset_id = $asset->id;
        $log->json_data = $asset_json;
        $log->user_id = $user_id;
        $log->save();
    }

    /**
     * @param $asset
     * @param $userId
     */
    private function createNewLogbookModel($asset, $userId)
    {
        $logbook = Logbook::where("asset_id", $asset->id)->first();
        $assetJsonBefore = json_encode($asset, true);

        if ($logbook == null || !$logbook->exists()) {
            $log = new Logbook;
            $log->asset_id = $asset->id;
            $log->json_data = $assetJsonBefore;
            $log->user_id = $userId;
            $log->save();
        }
    }

    /**
     * @param $assetId
     */
    public function destroyLogbook($assetId)
    {
        Logbook::where('asset_id', $assetId)->delete();
        LogbookReverted::where('asset_id', $assetId)->delete();
    }

    /**
     * @param $properties
     * @param $asset
     */
    private function createProperty($asset, $properties)
    {
        if ($properties) {
            foreach ($properties as $property) {
                $asset->properties()->save(new AssetProperty([
                    'name' => $property['name'],
                    'value' => $property['value'],
                ]));
            }
        }
    }

    /**
     * @param $asset
     * @param $properties
     */
    public function updateProperty($asset, $properties)
    {
        if ($properties) {
            $db_properties = $asset->properties;
            foreach ($properties as $property) {
                $propertyModel = $this->getPropertyFromCollection($db_properties, $property['name']);
                if (!$propertyModel) {
                    $asset->properties()->save(new AssetProperty([
                        'name' => $property['name'],
                        'value' => $property['value'],
                    ]));
                } elseif ($propertyModel->value != $property['value']) {
                    $propertyModel->update([
                        'value' => $property['value'],
                    ]);
                }
            }
            foreach ($db_properties as $property) {
                // if $property->name not in $properties
                $property->delete();
            }
        }
    }

    /*
     * If exists, the key is pulled from the collection and its value is returned.
     * If not exists it returns null.
     */
    private function getPropertyFromCollection($col, $name)
    {
        $key = $col->search(function ($item) use ($name) {
            return $item->name == $name;
        });

        return $col->pull($key);
    }
}
