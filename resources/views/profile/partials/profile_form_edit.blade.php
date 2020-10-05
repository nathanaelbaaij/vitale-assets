<div class="box">

    <div class="box-header with-border">
        <h3 class="box-title">Profiel wijzigen</h3>
        <span class="pull-right required-msg text-muted">
            <i class="fa fa-asterisk"></i> Deze velden zijn verplicht
        </span>
    </div>

    <form method="POST" action="{{ route('profile.update', $user->id) }}">

        @method('PUT')
        @csrf

        <div class="box-body">

            <div class="form-group">
                <label class="required" for="name">Naam</label>
                <input type="text" class="form-control" id="name" placeholder="Naam" name="name"
                       value="{{ $user->name }}">
            </div>

            <div class="form-group">
                <label class="required" for="email">E-mailadres</label>
                <input type="email" class="form-control" id="email" placeholder="E-mailadres" name="email"
                       value="{{ $user->email }}">
            </div>

            <hr>

            <div class="form-group">
                <label for="company">Bedrijfsnaam</label>
                <input type="text" class="form-control" id="company" placeholder="" name="company"
                       value="{{ $user->company }}">
            </div>

            <div class="row form-group">
                <div class="col-md-8">
                    <label for="adres">Adres</label>
                    <input type="text" class="form-control" id="adres" placeholder="" name="adres"
                           value="{{ $user->adres }}">
                </div>
                <div class="col-md-4">
                    <label for="houseNumber">Huisnummer</label>
                    <input type="text" class="form-control" id="houseNumber" placeholder="Bijv. 10-C"
                           name="house_number" value="{{ $user->house_number }}">
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-8">
                    <label for="city">Stad</label>
                    <input type="text" class="form-control" id="city" placeholder="" name="city"
                           value="{{ $user->city }}">
                </div>
                <div class="col-md-4">
                    <label for="postal">Postcode</label>
                    <input type="text" class="form-control" id="postal" placeholder="" name="postal"
                           value="{{ $user->postal }}">
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Telefoonnumer</label>
                <input type="text" class="form-control" id="phone" placeholder="" name="phone"
                       value="{{ $user->phone }}">
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-success pull-right">Profiel wijzigen</button>
        </div>

    </form>

</div>
