@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}">
                        @if(isset($breadcrumb->icon))
                            {!! $breadcrumb->icon !!} {{ $breadcrumb->title }}
                        @else
                            {{ $breadcrumb->title }}
                        @endif
                    </a>
                </li>
            @else
                <li class="active">
                    @if(isset($breadcrumb->icon))
                        {!! $breadcrumb->icon !!} {{ $breadcrumb->title }}
                    @else
                        {{ $breadcrumb->title }}
                    @endif
                </li>
            @endif

        @endforeach
    </ol>

@endif