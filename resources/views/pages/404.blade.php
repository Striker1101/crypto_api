@extends('pages.layout.app')

@section('title', '404 (Not Found)')

@section('content')

    <div data-elementor-type="wp-page" data-elementor-id="7" class="elementor elementor-7">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-61182e1 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="61182e1" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-container elementor-column-gap-no">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6854381"
                    data-id="6854381" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-d3201ec elementor-widget__width-auto elementor-widget elementor-widget-heading"
                            data-id="d3201ec" data-element_type="widget" data-widget_type="heading.default">
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
                                <h2 class="elementor-heading-title elementor-size-default">ERROR</h2>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-827bfbe elementor-widget elementor-widget-heading"
                            data-id="827bfbe" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">404</h2>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3f140eb elementor-widget elementor-widget-heading"
                            data-id="3f140eb" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h2 class="elementor-heading-title elementor-size-default">The Page is Missing</h2>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-b9f2619 elementor-widget elementor-widget-text-editor"
                            data-id="b9f2619" data-element_type="widget" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <style>
                                    /*! elementor - v3.17.0 - 08-11-2023 */

                                    .elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap {
                                        background-color: #69727d;
                                        color: #fff
                                    }

                                    .elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap {
                                        color: #69727d;
                                        border: 3px solid;
                                        background-color: transparent
                                    }

                                    .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap {
                                        margin-top: 8px
                                    }

                                    .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter {
                                        width: 1em;
                                        height: 1em
                                    }

                                    .elementor-widget-text-editor .elementor-drop-cap {
                                        float: left;
                                        text-align: center;
                                        line-height: 1;
                                        font-size: 50px
                                    }

                                    .elementor-widget-text-editor .elementor-drop-cap-letter {
                                        display: inline-block
                                    }
                                </style> Oops! The page you're looking for doesn't exist or has been moved
                                Please check the URL or return to the homepage
                                If you need help, feel free to contact us
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-085d482 elementor-align-center elementor-widget elementor-widget-button"
                            data-id="085d482" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                        href="{{ route('homepage') }}">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-text">Back To Home</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
