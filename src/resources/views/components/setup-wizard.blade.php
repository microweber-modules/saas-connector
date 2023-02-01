@extends('app::public.layout')


@section('content')



    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Choose a template</h1>
            </div>
        </div>
        <div class="row mt-4">
            @foreach($siteTemplates as $template)

                <div class="col-md-2">
                    <h4>{{$template['name']}}</h4>

                    @if(isset($template['screenshot']))
                        <div style="max-height:200px;overflow:hidden;">
                         <img src="{{$template['screenshot']}}" class="img-responsive" />
                        </div>
                    @endif
                    <button type="button" class="btn mt-3 btn-primary">
                        Start With This Template
                    </button>
                </div>

            @endforeach
        </div>
    </div>



@endsection
