@extends('adminlte::page')

@section('title', 'Nieuwsberichten')

@section('content_header')
    <h1>Timeline</h1>
    {{ Breadcrumbs::render('newsposts') }}
@stop

@section('content')

    <!-- foutmelding -->
    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-6">
            <ul class="timeline">
                @if(count($newsposts) > 0)
                    @php($currentDate = "")
                    @foreach($newsposts as $newspost)
                        @if($newspost->created_at->format('d/m/Y') !== $currentDate)
                            @php($currentDate = $newspost->created_at->format('d/m/Y'))
                            <li class="time-label" style="width: 10%;">
                                <span class="bg-red">{{$newspost->created_at->format('d/m/Y')}}</span>
                            </li>
                            @foreach($newsposts as $newspost)
                                @if($newspost->created_at->format('d/m/Y') == $currentDate)
                                    <li>
                                        @switch($newspost->category->id)
                                            @case(1)
                                            <i class="fa fa-exclamation-triangle bg-red"></i>
                                            @break
                                            @case(2)
                                            <i class="fa fa-exclamation bg-yellow"></i>
                                            @break
                                            @case(3)
                                            <i class="fa fa-check bg-green"></i>
                                            @break
                                            @case(4)
                                            <i class="fa fa-envelope bg-blue"></i>
                                            @break
                                        @endswitch
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fa fa-clock-o"></i>
                                                {{$newspost->created_at->format('H:i:s')}}
                                            </span>
                                            <h3 class="timeline-header">
                                                <a href="{{route('users.show', $newspost->user_id)}}">{{$newspost->user->name}}</a>
                                                - {{$newspost->title}}
                                            </h3>
                                            <div class="timeline-body"
                                                 style="word-break: break-all;">{{$newspost->message}}</div>
                                            <div class="timeline-footer">
                                                <div class="box-body">
                                                    <div class="pull-right">
                                                        @can('news-edit')
                                                            <a href="{{route('news.edit', $newspost->id)}}"
                                                               class="btn btn-warning btn-sm">Wijzigen</a>
                                                        @endcan
                                                        @can('news-delete')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                    data-toggle="modal" data-target="#delete">
                                                                Verwijderen
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                @endif
            </ul>
            @if(count($newsposts) == 0)
                <div>
                    <p><strong>Geen nieuwsberichten...</strong></p>
                </div>
            @endif
            <div class="box-body">
                <div class="pull-right">
                    {{$newsposts->links()}}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @can('news-create')
                <a href="{{route('news.create')}}" class="btn btn-success pull-right">Nieuwsbericht aanmaken</a>
            @endcan
        </div>
    </div>
@stop

@foreach($newsposts as $newspost)
    @includeIf('modals.delete', ['name' => 'bericht', 'action' => route('news.destroy', $newspost->id)])
@endforeach

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="/js/customDataTables.js"></script>
@stop