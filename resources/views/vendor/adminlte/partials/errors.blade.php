<!-- For validation message(s) errors -->
@if((is_array($errors) || is_object($errors)) && count($errors))
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Foutmelding</h3>
            <div class="box-body">
                <!-- error -->
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>

@else

    <!-- For session flash message(s) errors -->
    @if(session('errors'))
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Foutmelding</h3>
                <div class="box-body">
                    <!-- error -->
                    <ul>
                        <li>{{ session('errors') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif

@endif