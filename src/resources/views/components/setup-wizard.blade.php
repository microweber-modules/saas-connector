@extends('app::public.layout')


@section('content')


    <script>
        $(document).ready(function () {
            $('.js-start-with-this-template').on('click', function () {
                var template = $(this).data('template');
                $(this).html('Installing...');
                $.ajax({
                    url: '{{route('saas-connector.install-template')}}',
                    data: {
                        template: template
                    },
                    type: 'post',
                    success: function (response) {
                        if (response.success) {
                            window.location.href = '{{site_url()}}?editmode=y';
                        }
                    }
                });
            });
        });
    </script>


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

                         <img src="{{$template['screenshot']}}" class="img-responsive" />

                    @endif
                    <button type="button" data-template="{{$template['dir_name']}}" class="btn mt-3 btn-primary js-start-with-this-template">
                        Start With This Template
                    </button>
                </div>

            @endforeach
        </div>
    </div>



@endsection
