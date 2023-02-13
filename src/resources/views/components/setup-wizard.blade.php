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
            <div class="col-md-12">
                <h6 class="mw-templates-header text-center">Create New Site</h6>
                <p class="mw-templates-text text-center">Choose a sutable template and customize it to fit your <br> style and ideas</p>

                <div class="mw-process-categories-wrapper d-flex flex-wrap justify-content-center align-items-center">
                    <a <?php if (!isset($_GET['category'])): ?> class="active m-lg-0 m-2" <?php endif; ?> href="<?php echo site_url('setup-wizard'); ?>">
                        All
                    </a>

                    @foreach($siteTemplateCategories as $category)
                        <a <?php if (isset($_GET['category']) && $_GET['category'] == $category['slug']): ?> class="active m-lg-0 m-2" <?php endif; ?> href="?category={{$category['slug']}}">
                            {{$category['name']}}
                        </a>
                    @endforeach
                </div>

            </div>
            <div class="col-md-12 mx-auto">
                <div class="row">
                    @foreach($siteTemplates as $template)

                        <div class="col-xl-4 col-md-6 col-12">

                            <div class="mw-process-template-img-wrapper">

                                @if(isset($template['screenshot']))

                                    <div class="card">
                                        <div class="card-img-wrapper position-relative">
                                            <a href type="button" data-template="{{$template['dir_name']}}" class="btn mw-template-preview-btn">
                                                <img src="<?php print site_url(); ?>userfiles/modules/saas-connector/src/resources/views/img/mw-template-preview-eye.png" class="card-img-top" alt="templates-icon">

                                            </a>
                                            <img src="{{$template['screenshot']}}" class="card-img-top" alt="templates-img">
                                            <div class="card-img-overlay"></div>
                                        </div>

                                        <div class="card-body">
                                            <h6 class="mw-process-templates-title">{{$template['name']}}</h6>

                                            <p class="card-text">This template is great for gym, traders and more</p>

                                            <button type="button" data-template="{{$template['dir_name']}}" class="btn mt-3 btn-primary start-with-this-template js-start-with-this-template">
                                                Create
                                            </button>

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



@endsection
