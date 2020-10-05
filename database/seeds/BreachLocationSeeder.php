<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\BreachLocation;

/**
 * Class BreachLocationSeeder
 * @author Daan de Waard <dwaard@hz.nl>
 */
class BreachLocationSeeder extends Seeder
{
    protected $path = "import/breslocaties.csv";

    protected $header = ["xcoord", "ycoord", "id", "code", "name", "dykering", "longname", "vnk2"];

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        $contents = collect(explode("\n", Storage::get($this->path)));

        $firstRow = true;
        $header = collect();

        //loop through the data
        foreach ($contents as $raw) {
            //explode a row of data by the comma sign
            $row = explode(",", $raw);
            if ($firstRow) {
                // Create the header
                $header = collect($this->header);
                // set firstRow to false
                $firstRow = false;
            } else {
                if (count($row) == $header->count()) {
                    $this->command->info("Breslocatie: " . $row[4] . " is aangemaakt.");
                    // combine header and row into an Assiciate Array
                    $importData = $header->combine($row)->all();
                    // Save to the database
                    BreachLocation::create($importData);
                }
            }
        }
    }
}
