<form enctype="multipart/form-data" method="POST" action="{{ route('assets.update', $asset->id) }}" id="edit" name="form">

    @method('PUT')
    @csrf
    <input type="hidden" name="url" value="{{ url()->previous() }}"/>

    <input type="hidden" id="asset_id" name="asset_id" value="{{ $asset->id }}">
    <input type="hidden" id="xCoordHidden" name="xCoordHidden" value="{{ $asset->x_coordinate }}">
    <input type="hidden" id="yCoordHidden" name="yCoordHidden" value="{{ $asset->y_coordinate }}">

    <div class="box-body">
        <div class="form-group">
            <label class="required" for="assetName">Naam</label>
            <input type="text" class="form-control" id="assetName" placeholder="Naam" name="name"
                   value="{{ $asset->name }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="assetDescription">Beschrijving</label>
            <textarea name="description" id="assetDescription" class="form-control" rows="3" placeholder="Toelichting van de asset...">{{ $asset->description }}</textarea>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Kaart</label> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"
                                                   title="Klik op kaart om de asset locatie te bepalen of voer de locatie handmatig in via de invoervelden."></i>
                    <div id="map" class="map">
                        <div id="map-error"></div>
                        <div class="btn-group btn-group-map">
                            <button id="myCurrentLocationButton" type="button" class="btn btn-default btn-sm"><i
                                        class="fa fa-map-pin" aria-hidden="true"></i> Mijn locatie
                            </button>
                            <button id="resetButton" type="button" class="btn btn-default btn-sm"><i class="fa fa-undo" aria-hidden="true"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="required" for="xCoordinate">X-coördinaat</label>
                    <input name="xCoordinate" type="text" class="form-control" id="xCoordinate"
                           placeholder="xCoördinaat" value="{{ $asset->x_coordinate }}" required>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="required" for="yCoordinate">Y-coördinaat</label>
                    <input name="yCoordinate" type="text" class="form-control" id="yCoordinate"
                           placeholder="yCoordinaat" value="{{ $asset->y_coordinate }}" required>
                </div>
            </div>
        </div>

        <hr>

        <div class="form-group">
            <label class="required" for="category">Categorie</label>
            <select name="category" type="text" class="form-control" id="category" required>
                @foreach($categories as $category)
                    @if($category->id == $asset->category->id)
                        <option value="{{ $category->id }}" selected>{{$category->name}}</option>
                    @else
                        <option value={{ $category->id }}>{{ $category->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="threshold">Drempelwaarde <i class="fa fa-info-circle" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Een drempelwaarde is gekoppeld aan een categorie. Kies eerst een categorie om de desbetreffende drempelwaarde te zien."></i></label>
                    <div class="input-group">
                        <input name="threshold" type="number" step="any" class="form-control" id="threshold"
                               placeholder="" value="{{ $asset->category->threshold }}" disabled>
                        <span class="input-group-addon">m</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="thresholdCorrectionFormGroup" class="form-group">
                    <label for="thresholdCorrection">Drempelwaarde correctie</label>
                    <div class="input-group">
                        <input name="thresholdCorrection" type="number" step="any" class="form-control"
                               id="thresholdCorrection" placeholder="Drempelwaarde correctie"
                               value="{{ $asset->threshold_correction }}">
                        <span class="input-group-addon">m</span>
                    </div>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="thresholdReal">Drempelwaarde werkelijk</label>
                    <div class="input-group">
                        <input name="thresholdReal" type="number" step="any" class="form-control" id="thresholdReal"
                               placeholder=""
                               value="@if($asset->threshold_real){{$asset->threshold_real}}@else{{''}}@endif" disabled>
                        <span class="input-group-addon">m</span>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group" id="photo">
                    @if($assetPic)
                        <label for="picture">Foto</label></br>
                        <img alt="imageOfAsset" src="/uploads/assets/{{$assetPic->image}}" style="width: 50%">
                        <br>
                        <a onclick="deletePic()" class="btn btn-danger btn-sm" style="margin-top: 5px;"><i
                                    class="fa fa-trash" aria-hidden="true"></i> Verwijder foto</a>
                    @endif
                    <input type="hidden" id="check" name="check" value="true">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="assetPicture">Nieuwe foto</label>
                    <input type="file" name="assetPicture" id="upload">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="properties">Eigenschappen</label>
            <div id="setproperties">

            </div>
            <div id="inputproperties">

            </div>
            <div class="btn btn-default" onclick="addProperty()">
                <b style="font-size: large">+</b>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
        <button type="submit" class="btn btn-success pull-right">Asset wijzigen</button>
    </div>
</form>