<form enctype="multipart/form-data" action="{{ route('assets.store') }}" method="post" id="form" class="create">

    @csrf
    <input type="hidden" name="url" value="{{ url()->previous() }}"/>

    <input type="hidden" id="xCoordHidden" name="xCoordHidden" value="{{ old('xCoordinate') }}">
    <input type="hidden" id="yCoordHidden" name="yCoordHidden" value="{{ old('yCoordinate') }}">

    <div class="box-body" id="createform">
        <div class="form-group">
            <label class="required" for="assetName">Naam</label>
            
            <input name="name" type="text" class="form-control" id="assetName" placeholder="Naam" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="description">Beschrijving</label>
            <textarea name="description" type="text" class="form-control" id="description" rows="5" placeholder="Beschrijving">{{ old('description') }}</textarea>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Kaart</label> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Klik op kaart om de asset locatie te bepalen of voer de locatie handmatig in via de invoervelden."></i>
                    <div id="map" class="map">
                        <div id="map-error"></div>
                        <div class="btn-group btn-group-map">
                            <button id="myCurrentLocationButton" type="button" class="btn btn-default btn-sm"><i class="fa fa-map-pin" aria-hidden="true"></i> Mijn locatie</button>
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
                           placeholder="x-coördinaat" value="{{ old('xCoordinate') }}" required>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="required" for="yCoordinate">Y-coördinaat</label>
                    <input name="yCoordinate" type="text" class="form-control" id="yCoordinate"
                           placeholder="y-coördinaat" value="{{ old('yCoordinate') }}" required>
                </div>
            </div>
        </div>

        <hr>

        <div class="form-group">
            <label class="required" for="category">Categorie</label>
            <select name="category" type="text" class="form-control" id="category" required>
                <option value="" disabled selected>Kies een categorie</option>
                @foreach($categories as $category)
                    <option value={{ $category->id }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="threshold">Drempelwaarde <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Een drempelwaarde is gekoppeld aan een categorie. Kies eerst een categorie om de desbetreffende drempelwaarde te zien."></i></label>
                    <div class="input-group">
                        <input name="threshold" type="number" step="any" class="form-control" id="threshold" placeholder="" value="{{ old('threshold') }}" disabled>
                        <spdan class="input-group-addon">m</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="thresholdCorrectionFormGroup" class="form-group">
                    <label for="thresholdCorrection">Drempelwaarde correctie</label>
                    <div class="input-group">
                        <input name="thresholdCorrection" type="number" step="any" class="form-control" id="thresholdCorrection" placeholder="Drempelwaarde correctie" value="@if(old('thresholdCorrection') == null){{0}}@else{{old('thresholdCorrection')}}@endif">
                        <span class="input-group-addon">m</span>
                    </div>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="thresholdReal">Drempelwaarde werkelijk</label>
                    <div class="input-group">
                        <input name="thresholdReal" type="number" step="any" class="form-control" id="thresholdReal" placeholder="" value="" disabled>
                        <span class="input-group-addon">m</span>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="assetPicture">Foto</label>
                    <input type="file" name="assetPicture" value="{{old('assetPicture')}}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="properties">Eigenschappen</label>
            <div id="inputproperties"></div>
            <div class="btn btn-default" onclick="addProperty()"><b style="font-size: large">+</b></div>
        </div>
    </div>

    <div class="box-footer">
        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
        <button type="submit" class="btn btn-success pull-right">Asset toevoegen</button>
    </div>

</form>
