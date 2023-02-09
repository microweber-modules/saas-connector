@extends('app::public.layout')


@section('content')


    <style>
    @php
    $styleFile = modules_path() . 'saas-connector/src/resources/views/components/scss/styles.css';
    echo @file_get_contents($styleFile);
    @endphp
    </style>

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
            <div class="col-md-1">

                <div class="mw-process-categories-wrapper">

                    <a <?php if (!isset($_GET['category'])): ?> class="active" <?php endif; ?> href="<?php echo site_url('setup-wizard'); ?>">


                        All
                    </a>

                    @foreach($siteTemplateCategories as $category)
                        <a <?php if (isset($_GET['category']) && $_GET['category'] == $category['slug']): ?> class="active" <?php endif; ?> href="?category={{$category['slug']}}">

                            {{$category['name']}}</a>
                    @endforeach
                </div>

            </div>
            <div class="col-md-11">
                <div class="row">
                    @foreach($siteTemplates as $template)

                        <div class="col-xl-4 col-lg-6 col-12 p-0">

                            <div class="mw-process-template-img-wrapper position-relative" @if(isset($template['colors'][0])) style="background-color: {{$template['colors'][0]['hex']}} !important;" @endif>
                                <a href="" class="start-with-this-template-wrapper">
                                    <button type="button" data-template="{{$template['dir_name']}}" class="btn">
                                        START
                                    </button>
                                </a>

                                <a href="" class="preview-this-template-wrapper ">
                                    <button type="button" data-template="{{$template['dir_name']}}" class="btn">
                                        PREVIEW
                                    </button>
                                </a>

                                @if(isset($template['screenshot']))
                                    <div class="background-image-holder" style="background-image: url({{$template['screenshot']}})">
                                    </div>
                                @endif
                                <h6 class="mw-process-templates-title">{{$template['name']}}</h6>



                            </div>

                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>



@endsection
