<div class="box">

    <div class="box-header with-border">
        <h3 class="box-title">Recente profiel foto's</h3>
    </div>

    <div class="box-body">

        @if (isset($avatars_urls))
            <p>Je kunt op deze avatars klikken om deze avatar weer te gebruiken</p>
        @else
            <p>Je hebt op het moment nog geen avatars opgeslagen</p>
        @endif

        <div class="row">
            <div class="col-sm-12">
                @foreach ($avatars_urls as $avatar)
                    <a href="{{route('user.avatar.update', [$avatar->id])}}">
                        <img style="margin:10px; width:120px;" class="img-circle"
                             src="{{ asset('uploads/avatars/' . $avatar->image_url) }}">
                    </a>
                @endforeach
            </div>
        </div>

    </div>

</div>

<div class="box">

    <div class="box-header with-border">
        <h3 class="box-title">Profielfoto wijzigen</h3>
        <span class="pull-right required-msg text-muted">
            <i class="fa fa-asterisk"></i> Deze velden zijn verplicht
        </span>
    </div>

    <form enctype="multipart/form-data" method="POST" action="{{ route('user.avatar.store') }}">

        @csrf

        <div class="box-body">
            <div class="form-group">
                <label class="required" for="avatar">Afbeelding</label>
                <input type="file" name="avatar" id="avatar">
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="pull-right btn btn-success">Upload nieuwe profielfoto</button>
        </div>

    </form>

</div>