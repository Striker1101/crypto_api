@extends('pages.layout.app')

@section('title', 'Membership')

@section('content')

    <div data-elementor-type="wp-page" data-elementor-id="220" class="elementor elementor-220">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-d6ed419 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="d6ed419" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-container elementor-column-gap-no">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-a5d1eae"
                    data-id="a5d1eae" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-cd50af7 elementor-widget__width-auto elementor-widget elementor-widget-heading"
                            data-id="cd50af7" data-element_type="widget" data-widget_type="heading.default">
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
                                <h2 class="elementor-heading-title elementor-size-default">MEMBERSHIP</h2>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3a2f16a elementor-widget elementor-widget-heading"
                            data-id="3a2f16a" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">Join The Best Trading Broker
                                    Family</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-plan :plans="$plans" :datas="[
            'ef0c99b',
            '83fb486',
            'cdf660a',
            '176aa37',
            '106c979',
            '34d0426',
            'f5b56ba',
            'b8bb46b',
            '003d295',
            'd3c0d8d',
            '6d862f7',
            '6b2455a',
            '2b619e5',
            '1bd8b4b',
            '1bd8b4b',
        ]" />

        <x-features :datas="[
            '2a691fe',
            '5bdb67f',
            '2be10ab',
            'fbe5e71',
            '89e266b',
            '530c444',
            'e5dbbc7',
            '88fe007',
            '14a036f',
            'a1e4487',
            '3e80a11',
            '228cf37',
            'jeg_module_220_1_6811728c0d806',
            '1fef4a0',
            'd830bfc',
            '97044aa',
            '42896a1',
            'e642986',
            '442751c',
            'b9d7303',
            'dff5684',
            '174dcce',
            'fc29d66',
            '461ae55',
            'ea84e0a',
            'jeg_module_220_2_6811728c12efe',
            'c18528d',
            'jeg_module_220_3_6811728c13bcc',
            'ded7ffe',
            'jeg_module_220_4_6811728c14920',
            'e401d5f',
            'jeg_module_220_5_6811728c15847',
            '9ccab14',
            'bd563b8',
            'jeg_module_220_6_6811728c17b6a',
        ]" />

        <x-download-our-app :datas="['8ca9e1f', '3f5c4c6', 'f96e249', '9204d4a', 'a2747b9', 'ed7893b', '4bbda20', '042e6f4', '7deee66']" />

        <x-f-a-q :datas="[
            'b56dfa9',
            'dec0e9d',
            'b73579b',
            '18752ce',
            '3ace0f2',
            '3fcd503',
            '87a6ff0',
            'b68f661',
            '9f96d3d',
            '0648122',
            'jeg_module_220_7_6811728c23b7a',
        ]" />

    </div>
@endsection
