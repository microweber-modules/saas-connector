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


    <div class="container mw-process-templates">

        <div class="row mt-4">
            <div class="col-md-2">
                <b>Categories</b>
                <br />
                <a href="<?php echo site_url('setup-wizard'); ?>">All</a>
                <br />
            @foreach($siteTemplateCategories as $category)
                    <a href="?category={{$category['slug']}}">{{$category['name']}}</a> <br />
                @endforeach
            </div>
            <div class="col-md-10">
            <div class="row">
            @foreach($siteTemplates as $template)

                <div class="col-xl-4 col-md-6 col-12">

                    <h4>{{$template['name']}}</h4>

                    @if(isset($template['screenshot']))

                         <img src="{{$template['screenshot']}}" class="img-responsive" />

                    @endif

                    <div>

                        @if (!empty($template['categories']))
                            @foreach($template['categories'] as $category)
                                <a href="?category={{$category['slug']}}">{{$category['name']}}</a>
                            @endforeach
                            @php
                                $categoriesPlain = array_column($template['categories'], 'name');
                                $categoriesPlain = implode(', ', $categoriesPlain);
                            @endphp
                           <div>
                               <b>
                                   {{$categoriesPlain}}
                               </b>
                           </div>
                        @endif
                    </div>

                    <button type="button" data-template="{{$template['dir_name']}}" class="btn mt-3 btn-primary js-start-with-this-template">
                        Start With This Template
                    </button>
                </div>

            @endforeach
            </div>
            </div>
        </div>
    </div>



@endsection
