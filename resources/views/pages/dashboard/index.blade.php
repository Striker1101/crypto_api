@extends('pages.layout.dashboard')
@section('content')
    <div>
        <script>
            // Function to get the year backdated from the current year
            function getYear(offset) {
                const currentYear = new Date().getFullYear();
                return currentYear - offset;
            }

            // Function to get the current and previous year with months for labels
            function getYearMonthLabels() {
                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.toLocaleString('default', {
                    month: 'long'
                });
                const previousYear = currentYear - 1;
                const previousMonth = new Date(new Date().setFullYear(previousYear)).toLocaleString('default', {
                    month: 'long'
                });

                return [`${currentMonth} ${currentYear}`, `${previousMonth} ${previousYear}`];
            }

            function genRandom(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }


            // Generate data for line chart dynamically
            function generateLineChartData(years) {
                const data = [];
                for (let i = 0; i < years; i++) {
                    data.push({
                        y: getYear(i).toString(),
                        a: genRandom(1000, 100000),
                        b: genRandom(10, 30)
                    });
                }
                return data;
            }
        </script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Sample Toastr Notification
                setTimeout(function() {
                    var opts = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": rtl() || public_vars.$pageContainer.hasClass('right-sidebar') ?
                            "toast-top-left" : "toast-top-right",
                        "toastClass": "black",
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.success("You have Succesfully Logged into Your Dashboard, Happy trading!!", opts);
                }, 3000);

                // Sparkline Charts
                $('.inlinebar').sparkline('html', {
                    type: 'bar',
                    barColor: '#ff6264'
                });
                $('.inlinebar-2').sparkline('html', {
                    type: 'bar',
                    barColor: '#445982'
                });
                $('.inlinebar-3').sparkline('html', {
                    type: 'bar',
                    barColor: '#00b19d'
                });
                $('.bar').sparkline([
                    [1, 4],
                    [2, 3],
                    [3, 2],
                    [4, 1]
                ], {
                    type: 'bar'
                });
                $('.pie').sparkline('html', {
                    type: 'pie',
                    borderWidth: 0,
                    sliceColors: ['#3d4554', '#ee4749', '#00b19d']
                });
                $('.linechart').sparkline();
                $('.pageviews').sparkline('html', {
                    type: 'bar',
                    height: '30px',
                    barColor: '#ff6264'
                });
                $('.uniquevisitors').sparkline('html', {
                    type: 'bar',
                    height: '30px',
                    barColor: '#00b19d'
                });

                $(".monthly-sales").sparkline([1, 2, 3, 5, 6, 7, 2, 3, 3, 4, 3, 5, 7, 2, 4, 3, 5, 4, 5, 6, 3, 2], {
                    type: 'bar',
                    barColor: '#485671',
                    height: '80px',
                    barWidth: 10,
                    barSpacing: 2
                });

                // JVector Maps
                var map = $("#map");
                map.vectorMap({
                    map: 'europe_merc_en',
                    zoomMin: '3',
                    backgroundColor: '#383f47',
                    focusOn: {
                        x: 0.5,
                        y: 0.8,
                        scale: 3
                    }
                });

                // Line Charts
                var line_chart_demo = $("#line-chart-demo");
                var line_chart = Morris.Line({
                    element: 'line-chart-demo',
                    data: generateLineChartData(7), // 7 years backdated
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    labels: getYearMonthLabels(),
                    redraw: true
                });
                line_chart_demo.parent().attr('style', '');

                // Donut Chart
                var donut_chart_demo = $("#donut-chart-demo");
                donut_chart_demo.parent().show();
                var donut_chart = Morris.Donut({
                    element: 'donut-chart-demo',
                    data: [{
                            label: "Stocks Sales",
                            value: getRandomInt(200, 10)
                        },
                        {
                            label: "Forex Sales",
                            value: getRandomInt(500, 300)
                        },
                        {
                            label: "Crypto Sales",
                            value: getRandomInt(1000, 10)
                        }
                    ],
                    colors: ['#707f9b', '#455064', '#242d3c']
                });
                donut_chart_demo.parent().attr('style', '');

                // Area Chart
                var area_chart_demo = $("#area-chart-demo");
                area_chart_demo.parent().show();
                var area_chart = Morris.Area({
                    element: 'area-chart-demo',
                    data: generateLineChartData(7),
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    labels: getYearMonthLabels(),
                    lineColors: ['#303641', '#576277']
                });
                area_chart_demo.parent().attr('style', '');


                // Rickshaw
                var seriesData = [
                    [],
                    []
                ];
                var random = new Rickshaw.Fixtures.RandomData(50);
                for (var i = 0; i < 50; i++) {
                    random.addData(seriesData);
                }
                var graph = new Rickshaw.Graph({
                    element: document.getElementById("rickshaw-chart-demo"),
                    height: 193,
                    renderer: 'area',
                    stroke: false,
                    preserve: true,
                    series: [{
                        color: '#73c8ff',
                        data: seriesData[0],
                        name: 'Upload'
                    }, {
                        color: '#e0f2ff',
                        data: seriesData[1],
                        name: 'General market Trade'
                    }]
                });
                graph.render();
                var hoverDetail = new Rickshaw.Graph.HoverDetail({
                    graph: graph,
                    xFormatter: function(x) {
                        return new Date(x * 1000).toString();
                    }
                });
                var legend = new Rickshaw.Graph.Legend({
                    graph: graph,
                    element: document.getElementById('rickshaw-legend')
                });
                var highlighter = new Rickshaw.Graph.Behavior.Series.Highlight({
                    graph: graph,
                    legend: legend
                });
                setInterval(function() {
                    random.removeData(seriesData);
                    random.addData(seriesData);
                    graph.update();
                }, 500);
            });

            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        </script>
        <style>
            #defaulHolder {
                display: flex;
                justify-content: space-between;
                width: 100%;
                align-items: center;
            }

            .defaultButton {
                border: transparent;
                height: 30px;
                border-radius: 30px;
                color: white;
                padding: 10px;
                display: flex;
                background-color: #00c0ef;
                gap: 5px;
                font-size: large;
                align-items: center;
            }

            .defaultButton:hover {
                background: darkgray;
            }

            .liq {
                background-color: lightgray;
                padding: 15px;
                border-radius: 20px;
            }

            .liqBTW {
                display: flex;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 5px;
            }

            #liqContainer {
                display: flex;
                flex-direction: column;
                gap: 10px;
                color: black;
                font-size: larger;
                width: 100%;
            }

            .liq-number {
                border: transparent;
                width: 60px;
                border-radius: 30px;
                padding: 5px;

            }

            .liq-number:focus {
                border: transparent;
            }

            .selectOption {
                border: transparent;
                border-radius: 30px;
                padding: 5px;
            }
        </style>
        <div class="row">
            <div id="defaulHolder">
                <h1 style="font-size: xx-large;
                color: darkgrey;">
                    Default
                </h1>
                <div class="" style="display: flex; gap:6px;">
                    <button class="defaultButton btn">
                        <i class="fa fa-plus-circle" style="font-size: xx-large;"></i>
                        <a href="javascript:;" onclick="jQuery('#modal-3').modal('show');" class="btn btn-default">
                            Liquidity
                        </a>
                    </button>
                    <button class="defaultButton btn">
                        <a href="{{ route('user-buy-and-trade') }}">
                            Trade
                        </a>
                    </button>
                </div>
                <script>
                    function getSubstringAfterColon(input) {
                        const colonIndex = input.indexOf(':');
                        if (colonIndex !== -1) {
                            return input.substring(colonIndex + 1);
                        } else {
                            return input; // return the original string if no colon is found
                        }
                    }
                </script>

                @php
                    function getSubstringAfterColon($input)
                    {
                        $parts = explode(':', $input);
                        return isset($parts[1]) ? $parts[1] : $input;
                    }
                @endphp


                <div class="modal fade custom-width " id="modal-3" style="display: none;">
                    <div class="modal-dialog" style="width: 100%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Deposit Liquidity</h4>

                            </div>
                            <form action="{{ route('user-liquidate') }}" method="POST" accept-charset="utf-8"
                                enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <div class="modal-body" id="liqContainer" style="display: flex; flex-wrap:wrap;">
                                    <div id="fromLiq" class="liq">
                                        <div class="liqBTW">
                                            <span>From</span>
                                            <span>Balance:
                                                <span id="from-balance-amount"></span>
                                                <span id="from-balance-name"></span>
                                            </span>
                                        </div>
                                        <hr>
                                        <div class="liqBTW">
                                            <span>amount: <span>
                                                    <input required min="0" minlength="1" class="liq-number"
                                                        type="number" name="from-number" defaultValue=0 id="from-number">
                                                </span> </span>
                                            <span>
                                                <select name="fromOption" id="fromOption" class="selectOption">
                                                    <option data-name="{{ $member->currency }}"
                                                        data-amount="{{ $member->amount }}"
                                                        data-stock={{ getSubstringAfterColon('USD:USD') }} data-rate=10.2
                                                        value="{{ $member->id }} : user">
                                                        {{ $member->name }} {{ $member->amount }}
                                                    </option>
                                                    @foreach ($userStocks as $stock)
                                                        <option data-name="{{ $stock->name }}"
                                                            data-amount="{{ $stock->pivot->amount }}"
                                                            data-rate={{ $stock->rate }}
                                                            data-stock={{ getSubstringAfterColon($stock->name) }}
                                                            value="{{ $stock->id }} : stock">
                                                            {{ getSubstringAfterColon($stock->name) }}
                                                            {{ $stock->pivot->amount }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="midLiq"
                                        style="height:0px; width: 100%; display:flex; align-items: center; justify-content: center; position:relative;">
                                        <i class="fa fa-arrows" style="font-size: 20px;"></i>
                                    </div>
                                    <div id="toLiq" class="liq">
                                        <div class="liqBTW">
                                            <span>To</span>
                                            <span>Balance: <span id="to-balance-amount">

                                                </span> <span id="to-balance-name"></span></span>
                                        </div>
                                        <hr>
                                        <div class="liqBTW">
                                            <span>amount: <span>
                                                    <input min="0" minlength="1" class="liq-number" type="number"
                                                        name="to-number" id="to-number" readonly style="width: 90px;">
                                                </span> </span>
                                            <span>
                                                <select name="toOption" id="toOption" class="selectOption">

                                                    <option data-name-to="{{ $member->currency }}"
                                                        data-amount-to="{{ $member->amount }}" data-rate=10.2
                                                        data-stock={{ getSubstringAfterColon('USD:USD') }}
                                                        value="{{ $member->id }} : user ">
                                                        {{ $member->name }} {{ $member->amount }}
                                                    </option>
                                                    @foreach ($userStocks as $stock)
                                                        <option data-name-to="{{ $stock->name }}"
                                                            data-stock={{ getSubstringAfterColon($stock->name) }}
                                                            data-amount-to="{{ $stock->pivot->amount }}"
                                                            data-rate={{ $stock->rate }}
                                                            value="{{ $stock->id }} : stock">
                                                            {{ getSubstringAfterColon($stock->name) }}
                                                            {{ $stock->pivot->amount }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </span>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const selectElement = document.getElementById('fromOption');
                                                const selectElementTo = document.getElementById('toOption');

                                                const balanceAmountSpan = document.getElementById('from-balance-amount');
                                                const balanceNameSpan = document.getElementById('from-balance-name');
                                                const balanceAmountSpanTo = document.getElementById('to-balance-amount');
                                                const balanceNameSpanTo = document.getElementById('to-balance-name');

                                                const fromAmount = document.getElementById('from-number')
                                                const toAmount = document.getElementById('to-number')

                                                function converter(input) {
                                                    const selectOptionTo = selectElementTo.options[selectElementTo.selectedIndex]
                                                        .getAttribute(
                                                            'data-stock');

                                                    const selectOptionFrom = selectElement.options[selectElement.selectedIndex]
                                                        .getAttribute('data-stock');

                                                    const selectOptionFromRate = parseInt(selectElement.options[selectElement.selectedIndex]
                                                        .getAttribute('data-rate'));

                                                    const selectOptionToRate = parseInt(selectElementTo.options[selectElementTo
                                                            .selectedIndex]
                                                        .getAttribute('data-rate'));

                                                    if (input === "from") {
                                                        if (parseInt(fromAmount.value) > parseInt(selectElement.options[selectElement
                                                                    .selectedIndex]
                                                                .getAttribute('data-amount'))) {
                                                            fromAmount.style.cssText = "border:3px solid red; color:red;"
                                                        } else {
                                                            fromAmount.style.cssText = "border:transparent; color:black;"
                                                        }

                                                        const fromValue = parseFloat(fromAmount.value);
                                                        const rate = selectOptionFromRate * selectOptionToRate / 100
                                                        toAmount.value = (fromValue * rate).toFixed(2);

                                                    } else {
                                                        if (parseInt(toAmount.value) > parseInt(selectElementTo.options[selectElementTo
                                                                    .selectedIndex]
                                                                .getAttribute('data-amount'))) {

                                                            toAmount.style.cssText = "border:3px solid red; color:red;"
                                                        } else {
                                                            toAmount.style.cssText = "border:transparent; color:black;"
                                                        }

                                                        const toValue = parseFloat(toAmount.value);
                                                        const rate = selectOptionFromRate * selectOptionToRate / 100
                                                        fromAmount.value = (toValue * rate).toFixed(2);
                                                    }

                                                }

                                                toAmount.addEventListener('input', function() {
                                                    converter("to")
                                                })

                                                fromAmount.addEventListener('input', async function() {
                                                    converter('from')
                                                })

                                                selectElement.addEventListener('change', function() {

                                                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                                                    const name = selectedOption.getAttribute('data-name');
                                                    const amount = selectedOption.getAttribute('data-amount');

                                                    balanceAmountSpan.textContent = amount;
                                                    balanceNameSpan.textContent = name;
                                                    toAmount.value = 0
                                                });

                                                // Trigger change event to initialize with the first option's values
                                                selectElement.dispatchEvent(new Event('change'));

                                                selectElementTo.addEventListener('change', function() {
                                                    const selectedOption = selectElementTo.options[selectElementTo.selectedIndex];
                                                    const name = selectedOption.getAttribute('data-name-to');
                                                    const amount = selectedOption.getAttribute('data-amount-to');

                                                    balanceAmountSpanTo.textContent = amount;
                                                    balanceNameSpanTo.textContent = name;
                                                    fromAmount.value = 0
                                                });

                                                // Trigger change event to initialize with the first option's values
                                                selectElementTo.dispatchEvent(new Event('change'));
                                            });
                                        </script>
                                    </div>
                                    <hr>
                                    <div>
                                        <b style="font-weight: 600;">Slippage Tolerance</b>
                                        <div
                                            style="position: relative; margin:5px; display:flex; align-items:center; justify-content: center;">
                                            <label for="slippage" style="position: relative; top;0; left:0;">
                                                <i class="fa fa-percent" style="font-size: 15px;"></i>
                                            </label>
                                            <input type="text" disabled value="8.5"
                                                style="border: transparent; width:100%; padding:4px;">
                                        </div>
                                        <div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-default" data-dismiss="modal" type="reset">Close</button>
                                    <input class="btn btn-default" type="submit" value="Provide Liquidity" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <hr>
        </div>
        <div class="row">
            <div class="col-sm-3 col-xs-6">
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="entypo-users"></i></div>
                    <div class="d-flex " style="display:flex; align-items: center; gap:4px;">
                        <span style="font-size: xx-large;">
                            {{ $member->symbol }} <span><sup>{{ $member->currency }}</sup></span>
                        </span>
                        <div class="num" data-start="0" data-end={{ $member->amount }} data-postfix
                            data-duration="1500" data-delay="0">
                            0</div>
                    </div>
                    <h3>Total Amount</h3>
                    <p>Join in on our investment train</p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <div class="d-flex " style="display:flex; align-items: center; gap:4px;">
                        <span style="font-size: xx-large;">
                            {{ $member->symbol }} <span><sup>{{ $member->currency }}</sup></span>
                        </span>
                        <div class="num" data-start="0" data-end={{ $member->profit }} data-postfix
                            data-duration="1500" data-delay="600">0
                        </div>
                    </div>
                    <h3>Total Profit</h3>
                    <p>gain more by upgrading your account.</p>
                </div>
            </div>
            <div class="clear visible-xs"></div>
            <div class="col-sm-3 col-xs-6">
                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="entypo-bucket"></i></div>
                    <div class="d-flex " style="display:flex; align-items: center; gap:4px;">
                        <span style="font-size: xx-large;">
                            {{ $member->symbol }} <span><sup>{{ $member->currency }}</sup></span>
                        </span>
                        <div class="num" data-start="0" data-end={{ $member->bonus }} data-postfix
                            data-duration="1500" data-delay="1200">0
                        </div>
                    </div>
                    <h3>Total Bonus</h3>
                    <p>earn coins with every penny you invest</p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-6">
                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="entypo-rss"></i></div>
                    <div class="d-flex " style="display:flex; align-items: center; gap:4px;">
                        <span style="font-size: xx-large;">
                            {{ $member->symbol }} <span><sup>{{ $member->currency }}</sup></span>
                        </span>
                        <div class="num" data-start="0" data-end={{ $member->reference_bonus }} data-postfix
                            data-duration="1500" data-delay="1800">0
                        </div>
                    </div>
                    <h3>Referal Bonus</h3>
                    <p>invite your friends and family, and get payed </p>
                </div>
            </div>
        </div> <br />
        <div class="row">
            <style>
                #stock_container {
                    position: relative;
                    width: 100%;
                }

                #add_stock_container {
                    width: 300px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                #add_stock_container>a {

                    padding: 10px;
                    width: 100%;
                    border: 3px dashed gray;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;

                }

                #stock_item {
                    max-width: 300px;
                    position: relative;
                    margin: 8px;
                    padding: 3px;
                }

                #stock_item>span {
                    position: absolute;
                    top: 0;
                    right: 0;
                    display: flex;
                    background-color: greenyellow;
                    border-radius: 4px 0px 4px 4px;
                    padding: 4px;
                }

                #stock_holder {
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                }
            </style>
            <hr>
            <div id="stock_container ">



                <div id="stocks">
                    <div id="stock_holder">
                        @foreach ($userStocks as $stock)
                            <div id="stock_item">
                                <span style="font-size: xx-large;">
                                    <span>
                                        {{ $member->currency }}
                                    </span>
                                    <span>
                                        {{ $stock->pivot->amount }}
                                    </span>
                                </span>
                                {{-- Usage of the component --}}
                                @component('components.stock', ['stock' => $stock->name])
                                @endcomponent

                            </div>
                        @endforeach


                        <div id="add_stock_container">
                            <a href="javascript:;" onclick="jQuery('#modal-2').modal('show');" class="btn btn-default">
                                <i class="fa fa-plus-circle" style="font-size: 60px;">

                                </i>
                                <p style="color:black;">
                                    Add any financial coin and enjoy enpowering services
                                </p>
                                <h3 style="color: gray; padding:0; margin:0;">
                                    Add More
                                </h3>
                            </a>
                        </div>
                    </div>






                    <div class="modal fade custom-width " id="modal-2" style="display: none;">
                        <div class="modal-dialog" style="width: 50%;">
                            <div class="modal-content">
                                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                    <h4 class="modal-title">Add Or Remove Your Stocks</h4>
                                </div>
                                <div class="modal-body" style="display: flex; flex-wrap:wrap;">

                                    <div style="display: flex; flex-wrap:wrap;">

                                        @foreach ($stocks as $stock)
                                            <form method="PATCH" style="display:flex; flex-direction:column;"
                                                role="form" action="{{ route('stocks.toggle') }}">
                                                {{ csrf_field() }}
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">
                                                        {{ $stock->name }}</label>
                                                    <div class="col-sm-5">
                                                        <div class="make-switch has-switch"
                                                            data-on-label="<i class='entypo-check'></i>"
                                                            data-off-label="<i class='entypo-cancel'></i>">
                                                            <div
                                                                class="{{ $stock->status ? 'switch-on' : 'switch-off' }} switch-animate">
                                                                <input style="z-index:1000; width: 100%; height:100%;"
                                                                    type="checkbox" name="stock_status"
                                                                    {{ $stock->status ? 'checked' : '' }}
                                                                    id="stock_status" class="stock_status"><span
                                                                    class="switch-left"><i
                                                                        class="entypo-check"></i></span><label>&nbsp;</label><span
                                                                    class="switch-right"><i
                                                                        class="entypo-cancel"></i></span>
                                                                <input type="hidden" name="stock_id"
                                                                    value="{{ $stock->id }}">

                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $('input[name="stock_status"]').on('click', function() {
                                                                    console.log($(this).is(':checked'));
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                                <button type="submit" style="width: fit-content;"
                                                    class="btn btn-primary">Submit</button>
                                            </form>
                                        @endforeach

                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        $('.form-group').on('click', function() {
                                            var switchElement = $(this).find('.make-switch .switch-animate');

                                            var stock_status = $(this).find('.stock_status');




                                            if (stock_status.prop('checked')) {
                                                stock_status.prop('checked', false);
                                            } else {
                                                stock_status.prop('checked', true);
                                            }

                                            console.log(stock_status.prop('checked'));

                                            if (switchElement.hasClass('switch-off')) {
                                                switchElement.removeClass('switch-off').addClass('switch-on');
                                            } else if (switchElement.hasClass('switch-on')) {
                                                switchElement.removeClass('switch-on').addClass('switch-off');
                                            }
                                        });
                                    });
                                </script>

                                <div class="modal-footer"> <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-primary" id="charts_env">
                        <div class="panel-heading">
                            <div class="panel-title">{{ $general->title }} Daily Stats</div>
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class><a href="#area-chart" data-toggle="tab">Area Chart</a></li>
                                    <li class="active"><a href="#line-chart" data-toggle="tab">Line Charts</a></li>
                                    <li class><a href="#pie-chart" data-toggle="tab">Pie Chart</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane" id="area-chart">
                                    <div id="area-chart-demo" class="morrischart" style="height: 300px"></div>
                                </div>
                                <div class="tab-pane active" id="line-chart">
                                    <div id="line-chart-demo" class="morrischart" style="height: 300px"></div>
                                </div>
                                <div class="tab-pane" id="pie-chart">
                                    <div id="donut-chart-demo" class="morrischart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    @php
                                        function genRandom($min, $max)
                                        {
                                            return mt_rand($min, $max);
                                        }
                                    @endphp
                                    <th width="50%" class="col-padding-1">
                                        <div class="pull-left">
                                            <div class="h4 no-margin">Stocks Sold</div>
                                            <small>{{ genRandom(30000, 90000000) }}</small>
                                        </div> <span class="pull-right pageviews">4,3,5,4,5,6,5</span>
                                    </th>
                                    <th width="50%" class="col-padding-1">
                                        <div class="pull-left">
                                            <div class="h4 no-margin">Stock Purchased</div>
                                            <small>{{ genRandom(30000, 90000000) }}</small>
                                        </div> <span class="pull-right uniquevisitors">2,3,5,4,3,4,5</span>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4>
                                    Real Time {{ config('app.name') }} Stats
                                    <br /> <small>current server uptime</small>
                                </h4>
                            </div>
                            <div class="panel-options"> <a href="#sample-modal" data-toggle="modal"
                                    data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> <a
                                    href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> <a
                                    href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a> <a
                                    href="#" data-rel="close"><i class="entypo-cancel"></i></a> </div>
                        </div>
                        <div class="panel-body no-padding">
                            <div id="rickshaw-chart-demo">
                                <div id="rickshaw-legend"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr><br />
            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-primary">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th class="padding-bottom-none text-center"> <br /> <br /> <span
                                            class="monthly-sales"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="panel-heading">
                                        <h4>Monthly General Sales</h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">Latest Updated Profiles</div>
                            <div class="panel-options"> <a href="#sample-modal" data-toggle="modal"
                                    data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> <a
                                    href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> <a
                                    href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a> <a
                                    href="#" data-rel="close"><i class="entypo-cancel"></i></a> </div>
                        </div>
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Balance</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody id="profile-tbody">

                            </tbody>

                            <script>
                                $(document).ready(function() {
                                    // Function to generate a random balance between 4000 and 1 million
                                    function getRandomBalance() {
                                        return Math.floor(Math.random() * (1000000 - 4000 + 1)) + 4000;
                                    }

                                    // Function to generate random data
                                    function getRandomData() {
                                        const names = [
                                            "John Doe", "Jane Smith", "Emily Johnson", "Michael Brown", "Chris Davis",
                                            "Daniel Wilson", "Olivia Garcia", "Liam Martinez", "Emma Robinson", "Noah Lee",
                                            "Sophia Walker", "James Hall", "Isabella Young", "Benjamin Allen", "Ava Hernandez",
                                            "Mason King", "Mia Wright", "Elijah Scott", "Charlotte Green", "William Adams",
                                            "Amelia Baker", "Lucas Perez", "Harper Harris", "Ethan Campbell", "Abigail Mitchell",
                                            "Alexander Carter", "Evelyn Torres", "Sebastian Parker", "Avery Evans", "Jack Edwards"
                                        ];

                                        const stocks = [
                                            "BTC", "USDT", "TRON", "ETH", "LTC", "XRP", "DOGE", "ADA", "DOT", "UNI",
                                            "BNB", "SOL", "AVAX", "SHIB", "MATIC", "ATOM", "LINK", "FIL", "VET", "ICP",
                                            "Trader", "Investor", "Manager", "Analyst", "Consultant", "Forex", "Stocks",
                                            "Commodities", "Real Estate", "Bonds"
                                        ];

                                        const activities = [
                                            [4, 3, 5, 4, 5, 6],
                                            [1, 3, 4, 5, 3, 5],
                                            [5, 3, 2, 5, 4, 5],
                                            [3, 4, 5, 3, 2, 4],
                                            [2, 4, 5, 3, 1, 5]
                                        ];

                                        return {
                                            name: names[Math.floor(Math.random() * names.length)],
                                            stock: stocks[Math.floor(Math.random() * stocks.length)],
                                            balance: getRandomBalance(),
                                            activity: activities[Math.floor(Math.random() * activities.length)]
                                        };
                                    }

                                    // Function to populate the table body with random data
                                    function populateTable() {
                                        const tbody = $('#profile-tbody');
                                        tbody.empty(); // Clear existing content

                                        for (let i = 1; i <= 3; i++) {
                                            const data = getRandomData();
                                            const activityString = data.activity.join(',');
                                            const row = `
            <tr>
                <td>${i}</td>
                <td>${data.name}</td>
                <td>${data.stock}</td>
                <td class="text-center">${data.balance}</td>
                <td class="text-center"><span class="inlinebar-${i}"></span></td>
            </tr>
        `;
                                            tbody.append(row);

                                            // Draw the inline bar chart
                                            drawInlineBarChart(`.inlinebar-${i}`, data.activity);
                                        }
                                    }

                                    function drawInlineBarChart(selector, data) {
                                        const element = document.querySelector(selector);
                                        if (!element) return;

                                        const width = 100;
                                        const height = 20;
                                        const paper = Raphael(element, width, height);
                                        const max = Math.max(...data);

                                        data.forEach((value, index) => {
                                            const barHeight = (value / max) * height;
                                            paper.rect(index * (width / data.length), height - barHeight, width / data.length,
                                                barHeight).attr({
                                                fill: '#0b62a4'
                                            });
                                        });
                                    }


                                    // Initial population of the table
                                    populateTable();

                                    // Reload button functionality
                                    $('a[data-rel="reload"]').on('click', function(e) {
                                        e.preventDefault();
                                        populateTable();
                                    });
                                });
                            </script>
                        </table>
                    </div>
                </div>
            </div>
            <hr><br />
            <script type="text/javascript">
                // Code used to add Todo Tasks
                jQuery(document).ready(function($) {
                    function escapeHtml(text) {
                        var map = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        };
                        return text.replace(/[&<>"']/g, function(m) {
                            return map[m];
                        });
                    }
                    var $todo_tasks = $("#todo_tasks");
                    $todo_tasks.find('input[type="text"]').on('keydown', function(ev) {
                        if (ev.keyCode == 13) {
                            ev.preventDefault();
                            if ($.trim($(this).val()).length) {
                                var $todo_entry = $(
                                    '<li><div class="checkbox checkbox-replace color-white"><input type="checkbox" /><label>' +
                                    escapeHtml($(this).val()) + '</label></div></li>');
                                $(this).val('');
                                $todo_entry.appendTo($todo_tasks.find('.todo-list'));
                                $todo_entry.hide().slideDown('fast');
                                replaceCheckboxes();
                            }
                        }
                    });
                });
            </script>
            <div class="row">
                <div class="col-sm-3">
                    <div class="tile-block" id="todo_tasks">
                        <div class="tile-header"> <i class="entypo-list"></i> <a href="#">
                                Tasks
                                <span>To do list, tick one.</span> </a> </div>
                        <div class="tile-content">

                            <div class="card-body">
                                <form class="m-3" method="POST" action="{{ route('task.store') }}">
                                    {!! csrf_field() !!}

                                    <div class="form-group">
                                        <input id="taskInput" type="text"
                                            class="form-control @error('task') is-invalid @enderror" name="content"
                                            placeholder="Enter task here..." required autocomplete="task" autofocus>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add Task</button>
                                </form>
                            </div>


                            <ul class="todo-list">
                                @php
                                    use App\Task;

                                    //task
                                    $tasks = Task::where('user_id', Auth::user()->id)
                                        ->orderBy('created_at', 'asc')
                                        ->take(3)
                                        ->get();
                                    $task_count = Task::where('user_id', Auth::user()->id)->count();
                                @endphp
                                @foreach ($tasks as $task)
                                    <li>
                                        <form action="{{ url('user/tasks/' . $task->id . '/status') }}" method="PATCH"
                                            id="taskForm_{{ $task->id }}">
                                            {!! csrf_field() !!} <!-- Add CSRF token for Laravel forms -->

                                            <div class="checkbox checkbox-replace color-white">
                                                <input type="hidden" name="id" value="{{ $task->id }}">
                                                <input type="hidden" name="status" value="{{ $task->status }}">
                                                <!-- Hidden input for status -->

                                                <input type="checkbox" id="checkbox_task_{{ $task->id }}"
                                                    class="checkbox_task"
                                                    onchange="updateTaskStatus('{{ $task->id }}', this.checked)">
                                                <label
                                                    for="checkbox_task_{{ $task->id }}">{{ $task->content }}</label>
                                            </div>
                                        </form>

                                        <script>
                                            function updateTaskStatus(taskId, isChecked) {
                                                var form = document.getElementById('taskForm_' + taskId);
                                                form.querySelector('input[name="status"]').value = isChecked ? 1 : 0;
                                                form.submit();
                                            }
                                        </script>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tile-footer"> <a href="{{ route('user-task') }}">View all tasks</a> </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            var map = $("#map-2");
                            map.vectorMap({
                                map: 'europe_merc_en',
                                zoomMin: '3',
                                backgroundColor: '#383f47',
                                focusOn: {
                                    x: 0.5,
                                    y: 0.8,
                                    scale: 3
                                }
                            });
                        });
                    </script>
                    <div class="tile-group">
                        <div class="tile-left">
                            <div class="tile-entry">
                                <h3>Map</h3> <span>top visitors location</span>
                            </div>
                            <div class="tile-entry"> <img src="https://demo.neontheme.com/assets/images/sample-al.png" alt
                                    class="pull-right op" />
                                <h4>Albania</h4> <span>25%</span>
                            </div>
                            <div class="tile-entry"> <img src="https://demo.neontheme.com/assets/images/sample-it.png" alt
                                    class="pull-right op" />
                                <h4>Italy</h4> <span>18%</span>
                            </div>
                            <div class="tile-entry"> <img src="https://demo.neontheme.com/assets/images/sample-au.png" alt
                                    class="pull-right op" />
                                <h4>Austria</h4> <span>15%</span>
                            </div>
                        </div>
                        <div class="tile-right">
                            <div id="map-2" class="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
    @endsection
