<?php

use Illuminate\Database\Seeder;
use App\Asset;
use Illuminate\Support\Facades\Storage;


/**
 * Class AssetSeeder
 * @author Daan de Waard <dwaard@hz.nl>
 */
class AssetSeeder extends Seeder implements App\Services\AssetFileImportListener
{

    public function run(\App\Services\AssetFileImportService $fileImportService)
    {
        $fileImportService->setListener($this);

        $files = $this->getGeoJSONFiles();
        foreach ($files as $file) {
            try {
                $fileImportService->setData(Storage::get($file));
                $name = $fileImportService->getName();
                $count = $fileImportService->getFeatureCount();
                $this->command->getOutput()->write("Importing $name ($count):");
                $fileImportService->parse();
            } catch (\App\Exceptions\AssetImportException $e) {
                $this->command->getOutput()->write("*");
            } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
                $this->command->getOutput()->write("*");
            }
            $this->command->getOutput()->write("\n");
        }
    }

    /**
     * @return array
     * @return $matchingFiles
     */
    protected function getGeoJSONFiles()
    {
        $files = Storage::files("import");
        // filter the ones that match the *.geojson
        $matchingFiles = preg_grep('^(.*\.((geojson)$))^', $files);
        return $matchingFiles;
    }

    public function assetInserted(Asset $inserted): void
    {
        $this->command->getOutput()->write("+");
    }

    public function assetSkipped($asset_data): void
    {
        $this->command->getOutput()->write("-");
    }


 }
