<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\LoadLevel;

class LoadLevelSeeder extends Seeder
{
    protected $path = "import/belastingniveaus.csv";

    protected $header = ["id", "code", "name", "description"];

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        $contents = collect(explode("\n", Storage::get($this->path)));

        $firstRow = true;
        $header = collect();

        foreach ($contents as $row) {
            $row = explode(";", $row);
            if ($firstRow) {
                // Create the header
                $header = collect($this->header);
                $firstRow = false;
            } else {
                if (count($row) == $header->count()) {
                    $this->command->info("Importing: " . $row[1]);
                    // combine header and row into an Assiciate Array
                    $importData = $header->combine($row)->all();
                    // Save to the database
                    LoadLevel::create($importData);
                }
            }
        }
    }
}
