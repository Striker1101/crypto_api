@extends('pages.layout.app')

@section('title', 'Products')

@section('content')
    <div data-elementor-type="wp-page" data-elementor-id="218" class="elementor elementor-218">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-db7061d elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="db7061d" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-container elementor-column-gap-no">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-8317da5"
                    data-id="8317da5" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-a7bdcbf elementor-widget__width-auto elementor-widget elementor-widget-heading"
                            data-id="a7bdcbf" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <style>
                                    /*! elementor - v3.17.0 - 08-11-2023 */

                                    .elementor-heading-title {
                                        padding: 0;
                                        margin: 0;
                                        line-height: 1
                                    }

                                    .elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a {
                                        color: inherit;
                                        font-size: inherit;
                                        line-height: inherit
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-small {
                                        font-size: 15px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-medium {
                                        font-size: 19px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-large {
                                        font-size: 29px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-xl {
                                        font-size: 39px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-xxl {
                                        font-size: 59px
                                    }
                                </style>
                                <h2 class="elementor-heading-title elementor-size-default">PRODUCT</h2>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3b127a3 elementor-widget elementor-widget-heading"
                            data-id="3b127a3" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">Cover Every Trading Asset That
                                    You Like</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-trading-asset :datas="[
            'a9d2bae',
            'cd3d3dd',
            'f6474b2',
            '030a988',
            '2b4009b',
            'f27abdb',
            '6ddffd6',
            '01c53ce',
            'aaa5da5',
            '0097095',
            'jeg_module_218_1_6811723c95dd9',
            '67e5c39',
            '8f59e5d',
            'jeg_module_218_2_6811723c9745e',
            'f058ba3',
            '081214e',
            'jeg_module_218_3_6811723c98aca',
            '5db09bf',
            '8a95a98',
            '5abb331',
            'jeg_module_218_4_6811723c9a85c',
            'c8b6843',
            '3a5a755',
            'jeg_module_218_5_6811723c9c11e',
            '721c3d0',
            '5ba18e2',
            'jeg_module_218_6_6811723c9d798',
            '7ec34ae',
            '1264d07',
            '44efe8f',
        ]" />

        <x-plan :plans="$plans" :datas="[
            'f5d4283',
            'c60678a',
            '4af174f',
            '6501f3d',
            '4ee29ac',
            '74b8a0b',
            '25ddb63',
            'facc4b5',
            'ced0482',
            'f7feed8',
            'a308493',
            'cc1bfb3',
            '3135e9b',
            '93acb88',
            '8e842e5',
        ]" />

        <x-testimonies :datas="[
            '843c7f4',
            '6a6e816',
            '0190a3d',
            '9de6ede',
            'jeg_module_218_7_6811723cb8bdd',
            '384a358',
            '08dbb4d',
            '1f56305',
            '9e9fc60',
            '479b328',
            '616c277',
            '8f01835',
            'd1ce147',
            '8627a7b',
            '1894b29',
            'jeg_module_218_8_6811723cc0578',
            '3ebca54',
            '635b748',
            '002216b',
            'jeg_module_218_9_6811723cc297c',
            '9d532bf',
            '3d0c314',
        ]" />

        <x-blog-display :datas="[
            'f101ab3',
            'be34287',
            'a44e93d',
            '7e38aac',
            '9b3e648',
            '4e51e75',
            '935e628',
            '5645a91',
            '4ac7d51',
            'jeg_module_218_10_6811723cd02ea',
        ]" />
    </div>

@endsection
