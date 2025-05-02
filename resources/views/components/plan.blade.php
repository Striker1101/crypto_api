  @props(['plans'])

  <section
      class="elementor-section elementor-top-section elementor-element elementor-element-{{ $datas[0] }} elementor-section-boxed elementor-section-height-default elementor-section-height-default"
      data-id="{{ $datas[0] }}" data-element_type="section">
      <link rel="stylesheet"
          href="https://templatekit.jegtheme.com/tradiz/wp-content/plugins/elementor/assets/css/widget-icon-list.min.css">
      <div class="elementor-container elementor-column-gap-no">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-{{ $datas[1] }}"
              data-id="{{ $datas[1] }}" data-element_type="column">
              <div class="elementor-widget-wrap elementor-element-populated">
                  <section
                      class="elementor-section elementor-inner-section elementor-element elementor-element-{{ $datas[2] }} elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                      data-id="{{ $datas[2] }}" data-element_type="section">
                      <div class="elementor-container elementor-column-gap-no">
                          <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-{{ $datas[3] }}"
                              data-id="{{ $datas[3] }}" data-element_type="column">
                              <div class="elementor-widget-wrap elementor-element-populated">
                                  <div class="elementor-element elementor-element-{{ $datas[4] }} elementor-widget__width-auto elementor-widget elementor-widget-heading"
                                      data-id="{{ $datas[4] }}" data-element_type="widget"
                                      data-widget_type="heading.default">
                                      <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">OPEN ACCOUNT
                                          </h2>
                                      </div>
                                  </div>
                                  <div class="elementor-element elementor-element-{{ $datas[5] }} elementor-widget elementor-widget-heading"
                                      data-id="{{ $datas[5] }}" data-element_type="widget"
                                      data-widget_type="heading.default">
                                      <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Choose The
                                              Variety of Trading Account</h2>
                                      </div>
                                  </div>
                                  <div class="elementor-element elementor-element-{{ $datas[6] }} elementor-widget elementor-widget-text-editor"
                                      data-id="{{ $datas[6] }}" data-element_type="widget"
                                      data-widget_type="text-editor.default">
                                      <div class="elementor-widget-container">
                                          Choose from a wide range of trading accounts tailored to your needs.
                                          Find the perfect account type to suit your trading goals and strategies.
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </section>
                  <section
                      class="elementor-section elementor-inner-section elementor-element elementor-element-{{ $datas[7] }} elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                      data-id="{{ $datas[7] }}" data-element_type="section">
                      <div class="elementor-container elementor-column-gap-no">

                          @foreach ($plans as $plan)
                              <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-{{ $datas[8] }}"
                                  data-id="{{ $datas[8] }}" data-element_type="column">
                                  <div class="elementor-widget-wrap elementor-element-populated">
                                      <div class="elementor-element elementor-element-{{ $datas[9] }} elementor-widget elementor-widget-heading"
                                          data-id="{{ $datas[9] }}" data-element_type="widget"
                                          data-widget_type="heading.default">
                                          <div class="elementor-widget-container">
                                              <h2 class="elementor-heading-title elementor-size-default">
                                                  {{ $plan->name }}
                                              </h2>
                                          </div>
                                      </div>
                                      <div class="elementor-element elementor-element-{{ $datas[10] }} elementor-widget elementor-widget-text-editor"
                                          data-id="{{ $datas[10] }}" data-element_type="widget"
                                          data-widget_type="text-editor.default">
                                          <div class="elementor-widget-container">
                                              Type: {{ $plan->type }}
                                          </div>
                                      </div>
                                      <div class="elementor-element elementor-element-{{ $datas[10] }} elementor-widget__width-auto elementor-widget elementor-widget-text-editor"
                                          data-id="{{ $datas[11] }}" data-element_type="widget"
                                          data-widget_type="text-editor.default">
                                          <div class="elementor-widget-container">
                                              ALL LEVELS </div>
                                      </div>
                                      <div class="elementor-element elementor-element-{{ $datas[12] }} elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                                          data-id="{{ $datas[12] }}" data-element_type="widget"
                                          data-widget_type="divider.default">
                                          <div class="elementor-widget-container">

                                              <div class="elementor-divider">
                                                  <span class="elementor-divider-separator">
                                                  </span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="elementor-element elementor-element-{{ $datas[13] }} elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
                                          data-id="{{ $datas[13] }}" data-element_type="widget"
                                          data-widget_type="icon-list.default">
                                          <div class="elementor-widget-container">
                                              <ul class="elementor-icon-list-items">
                                                  <li class="elementor-icon-list-item">
                                                      <span class="elementor-icon-list-icon">
                                                          <i aria-hidden="true" class="fas fa-check-circle"></i> </span>
                                                      <span class="elementor-icon-list-text">Amount
                                                          ${{ $plan->amount }}
                                                      </span>
                                                  </li>
                                                  <li class="elementor-icon-list-item">
                                                      <span class="elementor-icon-list-icon">
                                                          <i aria-hidden="true" class="fas fa-check-circle"></i> </span>
                                                      <span class="elementor-icon-list-text"> Agents
                                                          {{ $plan->agent }}</span>
                                                  </li>
                                                  <li class="elementor-icon-list-item">
                                                      <span class="elementor-icon-list-icon">
                                                          <i aria-hidden="true" class="fas fa-check-circle"></i> </span>
                                                      <span class="elementor-icon-list-text">Support
                                                          {{ $plan->support }}</span>
                                                  </li>
                                                  <li class="elementor-icon-list-item">
                                                      <span class="elementor-icon-list-icon">
                                                          <i aria-hidden="true" class="fas fa-check-circle"></i> </span>
                                                      <span class="elementor-icon-list-text">Up to {{ $plan->percent }}
                                                          %Percent </span>
                                                  </li>
                                                  <li class="elementor-icon-list-item">
                                                      <span class="elementor-icon-list-icon">
                                                          <i aria-hidden="true" class="fas fa-check-circle"></i> </span>
                                                      <span class="elementor-icon-list-text">Duration
                                                          {{ $plan->duration }}
                                                      </span>
                                                  </li>
                                              </ul>
                                          </div>
                                      </div>
                                      <div class="elementor-element elementor-element-{{ $datas[14] }} elementor-align-justify elementor-widget elementor-widget-button"
                                          data-id="{{ $datas[14] }}" data-element_type="widget"
                                          data-widget_type="button.default">
                                          <div class="elementor-widget-container">
                                              <div class="elementor-button-wrapper">
                                                  <a class="elementor-button elementor-button-link elementor-size-sm"
                                                      href="{{ route('login') }}">
                                                      <span class="elementor-button-content-wrapper">
                                                          <span class="elementor-button-text">Open Account</span>
                                                      </span>
                                                  </a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                  </section>
              </div>
          </div>
      </div>



      <style>
          /*! elementor - v3.17.0 - 08-11-2023 */

          .elementor-widget-divider {
              --divider-border-style: none;
              --divider-border-width: 1px;
              --divider-color: #0c0d0e;
              --divider-icon-size: 20px;
              --divider-element-spacing: 10px;
              --divider-pattern-height: 24px;
              --divider-pattern-size: 20px;
              --divider-pattern-url: none;
              --divider-pattern-repeat: repeat-x
          }

          .elementor-widget-divider .elementor-divider {
              display: flex
          }

          .elementor-widget-divider .elementor-divider__text {
              font-size: 15px;
              line-height: 1;
              max-width: 95%
          }

          .elementor-widget-divider .elementor-divider__element {
              margin: 0 var(--divider-element-spacing);
              flex-shrink: 0
          }

          .elementor-widget-divider .elementor-icon {
              font-size: var(--divider-icon-size)
          }

          .elementor-widget-divider .elementor-divider-separator {
              display: flex;
              margin: 0;
              direction: ltr
          }

          .elementor-widget-divider--view-line_icon .elementor-divider-separator,
          .elementor-widget-divider--view-line_text .elementor-divider-separator {
              align-items: center
          }

          .elementor-widget-divider--view-line_icon .elementor-divider-separator:after,
          .elementor-widget-divider--view-line_icon .elementor-divider-separator:before,
          .elementor-widget-divider--view-line_text .elementor-divider-separator:after,
          .elementor-widget-divider--view-line_text .elementor-divider-separator:before {
              display: block;
              content: "";
              border-bottom: 0;
              flex-grow: 1;
              border-top: var(--divider-border-width) var(--divider-border-style) var(--divider-color)
          }

          .elementor-widget-divider--element-align-left .elementor-divider .elementor-divider-separator>.elementor-divider__svg:first-of-type {
              flex-grow: 0;
              flex-shrink: 100
          }

          .elementor-widget-divider--element-align-left .elementor-divider-separator:before {
              content: none
          }

          .elementor-widget-divider--element-align-left .elementor-divider__element {
              margin-left: 0
          }

          .elementor-widget-divider--element-align-right .elementor-divider .elementor-divider-separator>.elementor-divider__svg:last-of-type {
              flex-grow: 0;
              flex-shrink: 100
          }

          .elementor-widget-divider--element-align-right .elementor-divider-separator:after {
              content: none
          }

          .elementor-widget-divider--element-align-right .elementor-divider__element {
              margin-right: 0
          }

          .elementor-widget-divider:not(.elementor-widget-divider--view-line_text):not(.elementor-widget-divider--view-line_icon) .elementor-divider-separator {
              border-top: var(--divider-border-width) var(--divider-border-style) var(--divider-color)
          }

          .elementor-widget-divider--separator-type-pattern {
              --divider-border-style: none
          }

          .elementor-widget-divider--separator-type-pattern.elementor-widget-divider--view-line .elementor-divider-separator,
          .elementor-widget-divider--separator-type-pattern:not(.elementor-widget-divider--view-line) .elementor-divider-separator:after,
          .elementor-widget-divider--separator-type-pattern:not(.elementor-widget-divider--view-line) .elementor-divider-separator:before,
          .elementor-widget-divider--separator-type-pattern:not([class*=elementor-widget-divider--view]) .elementor-divider-separator {
              width: 100%;
              min-height: var(--divider-pattern-height);
              -webkit-mask-size: var(--divider-pattern-size) 100%;
              mask-size: var(--divider-pattern-size) 100%;
              -webkit-mask-repeat: var(--divider-pattern-repeat);
              mask-repeat: var(--divider-pattern-repeat);
              background-color: var(--divider-color);
              -webkit-mask-image: var(--divider-pattern-url);
              mask-image: var(--divider-pattern-url)
          }

          .elementor-widget-divider--no-spacing {
              --divider-pattern-size: auto
          }

          .elementor-widget-divider--bg-round {
              --divider-pattern-repeat: round
          }

          .rtl .elementor-widget-divider .elementor-divider__text {
              direction: rtl
          }

          .e-con-inner>.elementor-widget-divider,
          .e-con>.elementor-widget-divider {
              width: var(--container-widget-width, 100%);
              --flex-grow: var(--container-widget-flex-grow)
          }
      </style>
  </section>
