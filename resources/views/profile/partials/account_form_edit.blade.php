<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Wachtwoord wijzigen</h3>
        <span class="pull-right required-msg text-muted">
        <i class="fa fa-asterisk"></i> Deze velden zijn verplicht
    </span>
    </div>

    <form method="POST" action="{{ route('account.update', $user->id) }}">

        @method('PUT')
        @csrf

        <div class="box-body">

            {{-- Google chrome warning --}}
            <input type="text" name="email" value="{{ $user->email }}" autocomplete="username email" style="display: none;">

            <div class="form-group">
                <label class="required" for="old_password">Oud wachtwoord</label>
                <input type="password" required="required" class="form-control" id="old_password" name="old_password" autocomplete="current-password">
            </div>

            <div class="form-group">
                <label class="required" for="new_password">Nieuw wachtwoord</label>
                <input type="password" required="required" class="form-control" id="new_password" name="new_password" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label class="required" for="new_password_confirmation">Wachtwoord bevestiging</label>
                <input type="password" required="required" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-success pull-right">Wachtwoord wijzigen</button>
        </div>

    </form>
</div>