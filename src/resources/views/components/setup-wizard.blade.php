<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <title><?php _e('Resend'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>

    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>

    <script src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>

    <script>
        mw.lib.require('bootstrap5');
    </script>

</head>

<body>

<main class="w-100 h-100vh">
<link href="//fonts.googleapis.com/css?family=Inter:200,300,400,500,600,700,800,900" rel="stylesheet" />
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
    <div class="container mw-process-templates px-0">
        <div class="row mt-4">
            <div class="col-md-12">
                <h6 class="mw-templates-header text-center">Create New Site</h6>
                <p class="mw-templates-text text-center">Choose a sutable template and customize it to fit your <br> style and ideas</p>

                <?php

                $keywords = Array(
                    'business',
                    'services',
                    'store',
                    'events',
                    'nutrition',
                    'travel',
                    'press',
                    'hotel',
                    'education',

                );
                ?>

                <div class="mw-process-categories-wrapper row">
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <a <?php if (!isset($_GET['category'])): ?> class="active mw-process-categories-icons" <?php endif; ?> class="mw-process-categories-icons" href="<?php echo site_url('setup-wizard'); ?>">
                            All
                        </a>
                        <?php foreach($keywords as $keyword): ?>
                        <a class="active col-lg" href="?category=<?php echo $keyword; ?>">
                                <?php echo $keyword; ?>
                        </a>
                        <?php endforeach; ?>
                        <a href="" class=" mw-process-template-magnify mw-process-categories-icons d-flex justify-content-center align-items-center">
                            <img src="<?php print site_url(); ?>userfiles/modules/saas-connector/src/resources/views/img/mw-template-preview-magnify.png" alt="templates-icon">
                        </a>
                    </div>
                </div>

            </div>
            <div class="col-md-12 mx-auto">
                <div class="row">
                    @foreach($siteTemplates as $template)

                        <div class="col-xl-4 col-md-6 col-12">

                            <div class="mw-process-template-img-wrapper">

                                @if(isset($template['screenshot']))

                                    <div class="card">
                                        <div class="card-img-wrapper background-image-holder position-relative" style="background-image: url('{{$template['screenshot']}}')">

                                        </div>

                                        <div class="card-body">
                                            <h6 class="mw-process-templates-title">{{$template['name']}}</h6>

                                            <p class="card-text">This template is great for gym, traders and more</p>

                                            <a type="button" data-template="{{$template['dir_name']}}" class="btn mt-3 btn-primary start-with-this-template js-start-with-this-template">
                                                Create
                                            </a>

                                            <a href type="button" data-template="{{$template['dir_name']}}" class="btn mt-3 btn-primary start-with-this-template preview-template">
                                                 Preview
                                            </a>



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
</main>

</body>
</html>
