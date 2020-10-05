<?php
/**
 * Created by PhpStorm.
 * User: waar0003
 * Date: 26-11-2018
 * Time: 08:32
 */

namespace App\Services;

use App\Asset;

interface AssetFileImportListener
{

    /**
     * Called after an asset, with properties and depths have correctly been insert into the database.
     *
     * @param Asset $inserted
     */
    public function assetInserted(Asset $inserted) : void;

    /**
     * Called after it was decided that the asset is not inserted into the database.
     *
     * @param array $asset_data
     */
    public function assetSkipped($asset_data) : void;
}