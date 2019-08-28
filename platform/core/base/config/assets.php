<?php
/**
 * Created by Sublime Text 3.
 * User: Sang Nguyen
 * Date: 22/07/2015
 * Time: 8:11 PM
 */

return [
    'offline'        => env('ASSETS_OFFLINE', true),
    'enable_version' => env('ASSETS_ENABLE_VERSION', false),
    'version'        => env('ASSETS_VERSION', time()),
    'scripts'        => [
        'respond',
        'excanvas',
        'ie8.fix',
        'modernizr',
        'select2',
        'datepicker',
        'cookie',
        'core',
        'app',
        'toastr',
        'pace',
        'custom-scrollbar',
        'stickytableheaders',
        'jquery-waypoints',
        'spectrum',
        'fancybox',
    ],
    'styles'         => [
        'bootstrap',
        'fontawesome',
        'simple-line-icons',
        'select2',
        'pace',
        'toastr',
        'custom-scrollbar',
        'datepicker',
        'spectrum',
        'fancybox',
    ],
    'resources'      => [
        'scripts' => [
            'core'                   => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/js/core.js',
                ],
            ],
            'app'                    => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/js/app.js',
                ],
            ],
            'modernizr'              => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/modernizr/modernizr.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js',
                ],
            ],
            'respond'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/respond.min.js',
                ],
            ],
            'excanvas'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/excanvas.min.js',
                ],
            ],
            'textarea-autosize'      => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery.textarea_autosize.js',
                ],
            ],
            'ie8.fix'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/ie8.fix.min.js',
                ],
            ],
            'counterup'              => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/counterup/jquery.counterup.min.js',
                    ],
                ],
            ],
            'jquery-validation'      => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/jquery-validation/jquery.validate.min.js',
                        '/vendor/core/packages/jquery-validation/additional-methods.min.js',
                    ],
                ],
            ],
            'bootstrap-confirmation' => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-confirmation/bootstrap-confirmation.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js',
                ],
            ],
            'blockui'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery.blockui.min.js',
                ],
            ],
            'jquery-ui'              => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-ui/jquery-ui.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
                ],
            ],
            'cookie'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-cookie/jquery.cookie.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
                ],
            ],
            'jqueryTree'             => [
                'use_cdn'       => false,
                'location'      => 'footer',
                'include_style' => true,
                'src'           => [
                    'local' => '/vendor/core/packages/jquery-tree/jquery.tree.min.js',
                ],
            ],
            'floatThead'             => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/floatThead/floatThead.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/floatthead/1.4.0/jquery.floatThead.min.js',
                ],
            ],
            'bootstrap-editable'     => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap3-editable/js/bootstrap-editable.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js',
                ],
            ],
            'toastr'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/toastr/toastr.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.js',
                ],
            ],
            'pace'                   => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/pace/pace.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
            'fancybox'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/fancybox/jquery.fancybox.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js',
                ],
            ],
            'datatables'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/datatables/media/js/jquery.dataTables.min.js',
                        '/vendor/core/packages/datatables/media/js/dataTables.bootstrap.min.js',
                        '/vendor/core/packages/datatables/extensions/Buttons/js/dataTables.buttons.min.js',
                        '/vendor/core/packages/datatables/extensions/Buttons/js/buttons.bootstrap.min.js',
                    ],
                ],
            ],
            'bootstrap-tagsinput'    => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js',
                ],
            ],
            'bootstrap-pwstrength'   => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/pwstrength-bootstrap/pwstrength-bootstrap.min.js',
                ],
            ],
            'typeahead'              => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/typeahead.js/typeahead.jquery.min.js',
                        '/vendor/core/packages/typeahead.js/bloodhound.min.js',
                    ],
                    'cdn'   => [
                        '//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.jquery.min.js',
                        '//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/bloodhound.min.js',
                    ],
                ],
            ],
            'jvectormap'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/jvectormap/jquery-jvectormap-1.2.2.min.js',
                        '/vendor/core/packages/jvectormap/jquery-jvectormap-world-mill-en.js',
                    ],
                ],
            ],
            'raphael'                => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/raphael-min.js',
                    ],
                ],
            ],
            'morris'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/morris/morris.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
                ],
            ],
            'select2'                => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/select2/js/select2.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
            ],
            'cropper'                => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/cropper.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/cropper/0.7.9/cropper.min.js',
                ],
            ],
            'datepicker'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ],
            ],
            'sortable'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/sortable/sortable.min.js',
                ],
            ],
            'custom-scrollbar'       => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/mcustom-scrollbar/jquery.mCustomScrollbar.js',
                ],
            ],
            'stickytableheaders'     => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/stickytableheaders/jquery.stickytableheaders.js',
                ],
            ],
            'equal-height'           => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jQuery.equalHeights/jquery.equalheights.min.js',
                ],
            ],
            'jquery-nestable'        => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-nestable/jquery.nestable.js',
                ],
            ],
            'are-you-sure'           => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery.are-you-sure/jquery.are-you-sure.js',
                ],
            ],
            'moment'                 => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/moment-with-locales.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js',
                ],
            ],
            'datetimepicker'         => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js',
                ],
            ],
            'jquery-waypoints'       => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-waypoints/jquery.waypoints.min.js',
                ],
            ],
            'colorpicker'            => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
                ],
            ],
            'timepicker'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
                ],
            ],
            'spectrum'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/spectrum/spectrum.js',
                ],
            ],
            'input-mask'             => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-inputmask/jquery.inputmask.bundle.min.js',
                ],
            ],
            // End JS
        ],
        /* -- STYLESHEET ASSETS -- */
        'styles'  => [
            'bootstrap'           => [
                'use_cdn'    => true,
                'location'   => 'header',
                'src'        => [
                    'local' => '/vendor/core/packages/bootstrap/css/bootstrap.min.css',
                    'cdn'   => '//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
                ],
                'attributes' => [
                    'integrity'   => 'sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB',
                    'crossorigin' => 'anonymous',
                ],
            ],
            'fontawesome'         => [
                'use_cdn'    => true,
                'location'   => 'header',
                'src'        => [
                    'local' => '/vendor/core/packages/font-awesome/css/fontawesome.min.css',
                    'cdn'   => '//use.fontawesome.com/releases/v5.0.13/css/all.css',
                ],
                'attributes' => [
                    'integrity'   => 'sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp',
                    'crossorigin' => 'anonymous',
                ],
            ],
            'simple-line-icons'   => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/simple-line-icons/css/simple-line-icons.css',
                ],
            ],
            'core'                => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/css/core.css',
                ],
            ],
            'admin_bar'           => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/css/admin-bar.css',
                ],
            ],
            'jqueryTree'          => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-tree/jquery.tree.min.css',
                ],
            ],
            'videojs'             => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/videojs/video-js.min.css',
                    'cdn'   => '//vjs.zencdn.net/5.8/video-js.min.css',
                ],
            ],
            'jquery-ui'           => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-ui/jquery-ui.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css',
                ],
            ],
            'toastr'              => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/toastr/toastr.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css',
                ],
            ],
            'pace'                => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/pace/pace-theme-minimal.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-minimal.css',
                ],
            ],
            'kendo'               => [
                'use_cdn'  => false,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/kendo/kendo.min.css',
                ],
            ],
            'datatables'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/datatables/media/css/dataTables.bootstrap.min.css',
                    ],
                ],
            ],
            'bootstrap-editable'  => [
                'use_cdn'  => true,
                'location' => 'footer',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap3-editable/css/bootstrap-editable.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css',
                ],
            ],
            'bootstrap-tagsinput' => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/bootstrap-tagsinput/bootstrap-tagsinput.css',
                    ],
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css',
                ],
            ],
            'jvectormap'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/jvectormap/jquery-jvectormap-1.2.2.css',
                ],
            ],
            'morris'              => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/morris/morris.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
                ],
            ],
            'datepicker'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ],
            ],
            'select2'             => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => [
                        '/vendor/core/packages/select2/css/select2.min.css',
                        '/vendor/core/packages/select2/css/select2-bootstrap.min.css',
                    ],
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
                ],
            ],
            'fancybox'            => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/fancybox/jquery.fancybox.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css',
                ],
            ],
            'custom-scrollbar'    => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/mcustom-scrollbar/jquery.mCustomScrollbar.css',
                ],
            ],
            'jquery-nestable'     => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/jquery-nestable/jquery.nestable.css',
                ],
            ],
            'datetimepicker'      => [
                'use_cdn'  => true,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
                    'cdn'   => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css',
                ],
            ],
            'colorpicker'         => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
                ],
            ],
            'timepicker'          => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                ],
            ],
            'spectrum'            => [
                'use_cdn'  => false,
                'location' => 'header',
                'src'      => [
                    'local' => '/vendor/core/packages/spectrum/spectrum.css',
                ],
            ],
        ],
    ],
];
