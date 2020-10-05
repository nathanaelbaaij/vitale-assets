<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Exceptions\AssetImportException;
use App\Services\AssetFileImportListener;
use App\Services\AssetFileImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetImportController extends Controller implements AssetFileImportListener
{

    private $imported = 0;

    private $skipped = 0;


    public function create()
    {
        return view('assets.import');
    }

    public function store(Request $request, AssetFileImportService $service)
    {
        try {
            $file = $request->file('geojson_file');
            if ($file->isValid()) {
                $file->storeAs('tmp', $file->getClientOriginalName());
                $fd = Storage::get('tmp/' . $file->getClientOriginalName());
                $service->setListener($this);
                $service->setData($fd);
                $service->parse();
            }
        } catch (AssetImportException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect('categories')->with('message', "Aantal assets geïmporteerd: $this->imported; overgeslagen $this->skipped");
    }

    public function assetInserted(Asset $inserted): void
    {
        $this->imported++;
    }

    public function assetSkipped($asset_data): void
    {
        $this->skipped++;
    }


}
