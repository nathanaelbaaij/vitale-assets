<?php

namespace Tests\Unit;

use App\Exceptions\AssetImportException;
use App\Services\AssetFileImportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetFileImportServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $service;

    /**
     * Setup the test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new AssetFileImportService();
        $this->assertNotEmpty($this->service);
    }

    /**
     *
     * @expectedException App\Exceptions\AssetImportException
     */
    public function testItShouldThrowExceptionWhenEmptyDataIsSet()
    {
        $data = "";
        $this->service->setData($data);
    }

    public function testItShouldAcceptValidGeoJson()
    {
        $data = '{
            "type": "FeatureCollection",
            "name": "Afvalwater_RWZI",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:EPSG::3857" } },
            "features": [
            { "type": "Feature", "properties": { "name": "GM0703", "GM_NAAM": "Reimerswaal", "Code_ZRW": "ZRW7", "Benaming_R": "rwzi Waarde", "Opmerking": null, "WD_1_1": 0.77984, "WD_2_1": 3.15764, "WD_3_1": 1.86494, "WD_4_1": 2.79094, "WD_5_1": 1.55824, "WD_6_1": 0.0100625, "WD_7_1": 0.0, "WD_8_1": 0.0613605, "WD_24_1": 0.57054, "WD_1_2": 1.22064, "WD_2_2": 3.82234, "WD_3_2": 2.24904, "WD_4_2": 3.42814, "WD_5_2": 2.29794, "WD_6_2": 0.76534, "WD_7_2": 0.69654, "WD_8_2": 1.23104, "WD_9_2": 0.0, "WD_11_2": 0.0493584, "WD_12_2": 0.202763, "WD_13_2": 1.14194, "WD_14_2": 0.0, "WD_15_2": 0.00865843, "WD_16_2": 0.0, "WD_17_2": 0.0, "WD_18_2": 0.0, "WD_19_2": 0.0, "WD_20_2": 0.0, "WD_21_2": 0.0, "WD_22_2": 0.0, "WD_23_2": 0.0, "WD_24_2": 0.62054, "WD_1_3": 1.85524, "WD_2_3": 4.57424, "WD_3_3": 2.49964, "WD_4_3": 4.19424, "WD_5_3": 2.87484, "WD_6_3": 1.22764, "WD_7_3": 1.52454, "WD_8_3": 2.26144, "WD_11_3": 0.260997, "WD_12_3": 0.51234, "WD_13_3": 1.27654, "WD_14_3": 0.0, "WD_15_3": 0.37034, "WD_16_3": 0.0, "WD_17_3": 0.0, "WD_18_3": 0.0, "WD_19_3": 0.0, "WD_20_3": 0.0, "WD_21_3": 0.0, "WD_22_3": 0.0, "WD_23_3": 0.216079, "WD_24_3": 1.19604, "WD_11_4": 0.89024, "WD_12_4": 1.08264, "WD_13_4": 1.46074, "WD_14_4": 0.0, "WD_15_4": 1.06104, "WD_16_4": 0.0, "WD_17_4": 0.0, "WD_18_4": 0.0, "WD_19_4": 0.0, "WD_20_4": 0.00200537, "WD_22_4": 0.00999517 }, "geometry": { "type": "Point", "coordinates": [ 451234.651020197197795, 6697061.008017906919122 ] } }
            ]
            }
        ';
        $this->service->setData($data);
        $this->assertTrue(true);
    }

    /**
     *
     * @expectedException App\Exceptions\AssetImportException
     */
    public function testItShouldThrowExceptionWhenJsonHasNoTypeProperty()
    {
        $data = '{
            "typ": "FeatureCollection",
            "name": "Afvalwater_RWZI",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:EPSG::3857" } },
            "features": [
            { "type": "Feature", "properties": { "name": "GM0703", "GM_NAAM": "Reimerswaal", "Code_ZRW": "ZRW7", "Benaming_R": "rwzi Waarde", "Opmerking": null, "WD_1_1": 0.77984, "WD_2_1": 3.15764, "WD_3_1": 1.86494, "WD_4_1": 2.79094, "WD_5_1": 1.55824, "WD_6_1": 0.0100625, "WD_7_1": 0.0, "WD_8_1": 0.0613605, "WD_24_1": 0.57054, "WD_1_2": 1.22064, "WD_2_2": 3.82234, "WD_3_2": 2.24904, "WD_4_2": 3.42814, "WD_5_2": 2.29794, "WD_6_2": 0.76534, "WD_7_2": 0.69654, "WD_8_2": 1.23104, "WD_9_2": 0.0, "WD_11_2": 0.0493584, "WD_12_2": 0.202763, "WD_13_2": 1.14194, "WD_14_2": 0.0, "WD_15_2": 0.00865843, "WD_16_2": 0.0, "WD_17_2": 0.0, "WD_18_2": 0.0, "WD_19_2": 0.0, "WD_20_2": 0.0, "WD_21_2": 0.0, "WD_22_2": 0.0, "WD_23_2": 0.0, "WD_24_2": 0.62054, "WD_1_3": 1.85524, "WD_2_3": 4.57424, "WD_3_3": 2.49964, "WD_4_3": 4.19424, "WD_5_3": 2.87484, "WD_6_3": 1.22764, "WD_7_3": 1.52454, "WD_8_3": 2.26144, "WD_11_3": 0.260997, "WD_12_3": 0.51234, "WD_13_3": 1.27654, "WD_14_3": 0.0, "WD_15_3": 0.37034, "WD_16_3": 0.0, "WD_17_3": 0.0, "WD_18_3": 0.0, "WD_19_3": 0.0, "WD_20_3": 0.0, "WD_21_3": 0.0, "WD_22_3": 0.0, "WD_23_3": 0.216079, "WD_24_3": 1.19604, "WD_11_4": 0.89024, "WD_12_4": 1.08264, "WD_13_4": 1.46074, "WD_14_4": 0.0, "WD_15_4": 1.06104, "WD_16_4": 0.0, "WD_17_4": 0.0, "WD_18_4": 0.0, "WD_19_4": 0.0, "WD_20_4": 0.00200537, "WD_22_4": 0.00999517 }, "geometry": { "type": "Point", "coordinates": [ 451234.651020197197795, 6697061.008017906919122 ] } }
            ]
            }
        ';
        $this->service->setData($data);
    }

    /**
     *
     * @expectedException App\Exceptions\AssetImportException
     */
    public function testItShouldThrowExceptionWhenJsonIsNotFeaturecollection()
    {
        $data = '{
            "type": "FeatureCollections",
            "name": "Afvalwater_RWZI",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:EPSG::3857" } },
            "features": [
            { "type": "Feature", "properties": { "name": "GM0703", "GM_NAAM": "Reimerswaal", "Code_ZRW": "ZRW7", "Benaming_R": "rwzi Waarde", "Opmerking": null, "WD_1_1": 0.77984, "WD_2_1": 3.15764, "WD_3_1": 1.86494, "WD_4_1": 2.79094, "WD_5_1": 1.55824, "WD_6_1": 0.0100625, "WD_7_1": 0.0, "WD_8_1": 0.0613605, "WD_24_1": 0.57054, "WD_1_2": 1.22064, "WD_2_2": 3.82234, "WD_3_2": 2.24904, "WD_4_2": 3.42814, "WD_5_2": 2.29794, "WD_6_2": 0.76534, "WD_7_2": 0.69654, "WD_8_2": 1.23104, "WD_9_2": 0.0, "WD_11_2": 0.0493584, "WD_12_2": 0.202763, "WD_13_2": 1.14194, "WD_14_2": 0.0, "WD_15_2": 0.00865843, "WD_16_2": 0.0, "WD_17_2": 0.0, "WD_18_2": 0.0, "WD_19_2": 0.0, "WD_20_2": 0.0, "WD_21_2": 0.0, "WD_22_2": 0.0, "WD_23_2": 0.0, "WD_24_2": 0.62054, "WD_1_3": 1.85524, "WD_2_3": 4.57424, "WD_3_3": 2.49964, "WD_4_3": 4.19424, "WD_5_3": 2.87484, "WD_6_3": 1.22764, "WD_7_3": 1.52454, "WD_8_3": 2.26144, "WD_11_3": 0.260997, "WD_12_3": 0.51234, "WD_13_3": 1.27654, "WD_14_3": 0.0, "WD_15_3": 0.37034, "WD_16_3": 0.0, "WD_17_3": 0.0, "WD_18_3": 0.0, "WD_19_3": 0.0, "WD_20_3": 0.0, "WD_21_3": 0.0, "WD_22_3": 0.0, "WD_23_3": 0.216079, "WD_24_3": 1.19604, "WD_11_4": 0.89024, "WD_12_4": 1.08264, "WD_13_4": 1.46074, "WD_14_4": 0.0, "WD_15_4": 1.06104, "WD_16_4": 0.0, "WD_17_4": 0.0, "WD_18_4": 0.0, "WD_19_4": 0.0, "WD_20_4": 0.00200537, "WD_22_4": 0.00999517 }, "geometry": { "type": "Point", "coordinates": [ 451234.651020197197795, 6697061.008017906919122 ] } }
            ]
            }
        ';
        $this->service->setData($data);
    }

    public function testItShouldImportAnAssetSet()
    {
        $data = '{
            "type": "FeatureCollection",
            "name": "Afvalwater_RWZI",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:EPSG::3857" } },
            "features": [
            { "type": "Feature", "properties": { "name": "GM0703", "GM_NAAM": "Reimerswaal", "Code_ZRW": "ZRW7", "Benaming_R": "rwzi Waarde", "Opmerking": null, "WD_1_1": 0.77984, "WD_2_1": 3.15764, "WD_3_1": 1.86494, "WD_4_1": 2.79094, "WD_5_1": 1.55824, "WD_6_1": 0.0100625, "WD_7_1": 0.0, "WD_8_1": 0.0613605, "WD_24_1": 0.57054, "WD_1_2": 1.22064, "WD_2_2": 3.82234, "WD_3_2": 2.24904, "WD_4_2": 3.42814, "WD_5_2": 2.29794, "WD_6_2": 0.76534, "WD_7_2": 0.69654, "WD_8_2": 1.23104, "WD_9_2": 0.0, "WD_11_2": 0.0493584, "WD_12_2": 0.202763, "WD_13_2": 1.14194, "WD_14_2": 0.0, "WD_15_2": 0.00865843, "WD_16_2": 0.0, "WD_17_2": 0.0, "WD_18_2": 0.0, "WD_19_2": 0.0, "WD_20_2": 0.0, "WD_21_2": 0.0, "WD_22_2": 0.0, "WD_23_2": 0.0, "WD_24_2": 0.62054, "WD_1_3": 1.85524, "WD_2_3": 4.57424, "WD_3_3": 2.49964, "WD_4_3": 4.19424, "WD_5_3": 2.87484, "WD_6_3": 1.22764, "WD_7_3": 1.52454, "WD_8_3": 2.26144, "WD_11_3": 0.260997, "WD_12_3": 0.51234, "WD_13_3": 1.27654, "WD_14_3": 0.0, "WD_15_3": 0.37034, "WD_16_3": 0.0, "WD_17_3": 0.0, "WD_18_3": 0.0, "WD_19_3": 0.0, "WD_20_3": 0.0, "WD_21_3": 0.0, "WD_22_3": 0.0, "WD_23_3": 0.216079, "WD_24_3": 1.19604, "WD_11_4": 0.89024, "WD_12_4": 1.08264, "WD_13_4": 1.46074, "WD_14_4": 0.0, "WD_15_4": 1.06104, "WD_16_4": 0.0, "WD_17_4": 0.0, "WD_18_4": 0.0, "WD_19_4": 0.0, "WD_20_4": 0.00200537, "WD_22_4": 0.00999517 }, "geometry": { "type": "Point", "coordinates": [ 451234.651020197197795, 6697061.008017906919122 ] } }
            ]
            }
        ';
        $this->service->setData($data);
        $this->service->parse();
    }

    /**
     *
     * @expectedException App\Exceptions\AssetImportException
     */
    public function testItShouldThrowExceptionWhenNameIsNotPresent()
    {
        $data = "{}";
        $this->service->setData($data);
    }

    public function testParseValueShouldReturnZero()
    {
        self::assertEquals(0.0, $this->service->parseDepthValue("0"));
        self::assertEquals(0.0, $this->service->parseDepthValue("0.0"));
        self::assertEquals(0.0, $this->service->parseDepthValue("0.0"));
        self::assertEquals(0.0, $this->service->parseDepthValue(0.0));

    }


}
