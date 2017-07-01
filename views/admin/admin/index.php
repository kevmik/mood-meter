<?php
use app\assets\AdminAsset;
AdminAsset::register($this);

$this->registerJsFile(
	Yii::getAlias( '@web/web/js/utils.js' ),
	[ 'depends' => [ \yii\web\JqueryAsset::className() ] ]
);

$this->title = 'Building Dashboard';
?>
    <div class="site-index">

        <div class="text-center">
            <h1>Dashboard!</h1>
        </div>

        <div class="body-content">

            <div class="row">
                <h3 class="col-xs-12 text-center">Bubble chart</h3>
                <div class="col-xs-12 col-sm-6 filter_form_full">
                    <h2>Filter for bubble charts</h2>
                    <div class="col-xs-6">Date From: <input type="date" name="bubble.from" value="<?= date( 'Y-m-d') ?>"></div>
                    <div class="col-xs-6">Date To: <input type="date" name="bubble.to" value="<?= date( 'Y-m-d') ?>"></div>
                    <div class="col-xs-12 " style="margin: 10px 0;">
                        Buildings:
                        <select class="js-example-basic-multiple" multiple="multiple"
                                id="bubble_bids">
							<?php
							$selected = rand( 0, count( $buildings ) );
							$selected = 0;
							$s        = ( 0 == $selected ) ? 'selected="selected"' : '';
							echo "<option value=\"all\" selected=\"selected\" >all</option>";

							foreach ( $buildings as $i => $b ) {
								$s = ( $i + 1 == $selected ) ? 'selected="selected"' : '';
								echo "<option value=\"{$b->id}\" $s >{$b->name}</option>";
							} ?>
                        </select>
                    </div>
                    <div style="padding: 10px;">
                        <button class="btn btn-primary" id="bubble_submit">Send</button>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">

                    <div id="bubble_params"></div>
                    <div id="bubble-chart">
                        <canvas id="bubble_canvas" width="100%" height="100%"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <h2 class="col-xs-12 text-center">Line chart</h2>
                <div class="col-xs-12 col-sm-6 filter_form_full">
                    <h2>Filter for line charts</h2>
                    <div class="col-xs-6">Date From:
                        <input type="date" name="line.from" ></div>
                    <div class="col-xs-6">Date To: <input type="date" name="line.to" value="<?= date( 'Y-m-d') ?>">
                    </div>

                    <div class="col-xs-12 " style="margin: 10px 0;">
                        Buildings:
                        <select class="js-example-basic-multiple" multiple="multiple"
                                id="line_bids">
							<?php
							$selected = rand( 0, count( $buildings ) );
							$selected = 0;
							$s        = ( 0 == $selected ) ? 'selected="selected"' : '';
							echo "<option value=\"all\" $s >all</option>";

							foreach ( $buildings as $i => $b ) {
								$s = ( $i + 1 == $selected ) ? 'selected="selected"' : '';
								echo "<option value=\"{$b->id}\" $s >{$b->name}</option>";
							} ?>
                        </select>
                    </div>
                    <div style="padding: 10px;">
                         <button class="btn btn-primary" id="line_submit">Send</button>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">

                    <div id="line_params"></div>
                    <div id="line-chart">
                        <canvas id="line_canvas" width="100%" height="100%"></canvas>
                    </div>
                </div>
            </div>


            <!--            <div id="b-candel-chart"><h3>Candel chart</h3></div>-->
            <!--            <div id="b-plot-chart"><h3>Plot chart</h3></div>-->

        </div>

    </div>

    <style>
        .filter_form_full, .filter_form_b {
            border: 1px solid #999;
        }

        #bubble-chart {
            max-height: 600px;
            min-height: 400px;

        }
        .js-example-basic-multiple{
            min-width: 200px;
        }
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
    <script>
        function boobleLoad() {
            var bubble_ctx = document.getElementById("bubble_canvas");
            var from       = $("input[name=\"bubble.from\"]").val();
            var to         = $("input[name=\"bubble.to\"]").val();
            var bids       = $("#bubble_bids").val();
            $.ajax(
                {
                    url:      "/admin/admin/bubble",
                    type:     "POST",
                    dataType: "json",
                    data:     {from: from, to: to, bids: bids},
                    success:  function(data) {

                        new Chart(bubble_ctx, {
                            "type":  "bubble",
                            "data":  {
                                "datasets": [
                                    {
                                        "label":           "Energy & Pleasantness", "data": data.set1,
                                        "backgroundColor": "rgb(255, 99, 132)"
                                    },
                                    {
                                        "label": " ", "data": [{x: -10, y:-10, r: 1}, {x: 10, y: 10, r: 1}],
                                        "backgroundColor":    "rgb(255, 255, 255)"
                                    }
                                ]
                            },
                            options: {
                                legend: {
                                    display: false,
                                    labels:  {
                                        fontColor: "rgb(255, 99, 132)"
                                    }
                                }
                            }
                        });
                    }
                });
        }
        function lineChartShow(data) {
            var timeFormat = "YYYY-MM-DD HH";
            if (data.type == 'day'){
                timeFormat = "YYYY-MM-DD";
            }

            var line_ctx   = document.getElementById("line_canvas");
            var color      = Chart.helpers.color;
            var config     = {
                type:    "line",
                data:    {

                    datasets: [{
                        label:           "Energy",
                        backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                        borderColor:     window.chartColors.green,
                        fill:            false,
                        data:            data.result.energy
                    },
                        {
                            label:           "Pleasantness",
                            backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                            borderColor:     window.chartColors.red,
                            fill:            false,
                            data:            data.result.pleasantness
                        }
                    ]
                },
                options: {
                    title:  {
                        text: "Chart.js Time Scale"
                    },
                    scales: {
                        xAxes: [{
                            type:       "time",
                            time:       {
                                format:        timeFormat,
                                round:         data.type,
                                tooltipFormat: "ll HH"
                            },
                            scaleLabel: {
                                display:     true,
                                labelString: "Date"
                            }
                        },],
                        yAxes: [{
                            scaleLabel: {
                                display:     true,
                                labelString: "value"
                            }
                        }]
                    },
                }
            };
            new Chart(line_ctx, config);
        }
        function lineLoad() {
            var from = $("input[name=\"line.from\"]").val();
            var to   = $("input[name=\"line.to\"]").val();
            var bids = $("#line_bids").val();
            $.ajax(
                {
                    url:      "/admin/admin/line",
                    type:     "POST",
                    dataType: "json",
                    data:     {from: from, to: to, bids: bids},
                    success:  lineChartShow
                });
        }
        function initPage() {
            $(".js-example-basic-multiple").select2();
            $("#bubble_submit").click(boobleLoad);
            $("#line_submit").click(lineLoad);
            var bubble_select = $("#bubble_bids").val();
            var line_select   = $("#line_bids").val();
            $("select#bubble_bids").change(function(e) {
                var val = $("#bubble_bids").val();
                if (bubble_select.indexOf("all") > -1 && val.length > 1) {
                    val.splice(0, 1);
                    $(this).val(val).trigger("change");
                } else if (val[0] == "all" && val.length > 1) {
                    val = ["all"];
                    $(this).val(val).trigger("change");
                }
                bubble_select = val;
            });
            $("select#line_bids").change(function(e) {
                var val = $("#line_bids").val();
                if (line_select.indexOf("all") > -1 && val.length > 1) {
                    val.splice(0, 1);
                    $(this).val(val).trigger("change");
                } else if (val[0] == "all" && val.length > 1) {
                    val = ["all"];
                    $(this).val(val).trigger("change");
                }
                line_select = val;
            });
            boobleLoad();
            lineLoad();
        }
    </script>
<?php
$js = '
  
       $(document).ready(function() {initPage();});
';
$this->registerJs( $js );
