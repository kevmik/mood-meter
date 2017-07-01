<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register( $this );
$building = $this->params['building'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode( $this->title ) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<?php
$dims = 21;
function colorsBeetWin( $c1, $c2, $offset, $dims ) {

	$r = $c1[0] - ( ( $c1[0] - $c2[0] ) / ( $dims - 1 ) ) * $offset;
	$g = $c1[1] - ( ( $c1[1] - $c2[1] ) / ( $dims - 1 ) ) * $offset;
	$b = $c1[2] - ( ( $c1[2] - $c2[2] ) / ( $dims - 1 ) ) * $offset;

	return [ $r, $g, $b ];
}

$size   = 21;
$colors = [
	[ 'fe0000', 'fe2501', 'fe4401', 'fe5e00', 'ff7a01', 'ffb600', 'fdc300', 'fbce01', 'f8db01', 'f5e700' ],
	[ 'ed0908', 'ee2708', 'ee3f08', 'ed5309', 'ee6a08', 'f9b806', 'f7bf06', 'f5cb07', 'f3d407', 'f2dd06' ],
	[ 'dd1112', 'dd2711', 'dc3910', 'de4911', 'dd5b10', 'f1b70d', 'f1be0b', 'efc60c', 'eecd0c', 'ecd50b' ],
	[ 'd01818', 'd02917', 'd03718', 'd04318', 'd04f18', 'ecb711', 'ecbc10', 'ebc212', 'eac811', 'e9cd11' ],
	[ 'c21e1d', 'c3291f', 'c3321f', 'c43a1e', 'c3431e', 'e9b716', 'e8bb17', 'e8bd16', 'e6c216', 'e6c717' ],

	[ '072dd6', '0f4fca', '196bc1', '2089b8', '29a3ae', '77bd36', '77bb30', '78b92b', '77b725', '77b621' ],
	[ '0b2bb4', '1546ac', '1a5da4', '21749c', '288a95', '5cb43a', '5db233', '5daf29', '5eac22', '5dab1a' ],
	[ '122993', '183d8d', '1c4f88', '216081', '28707c', '44ab3e', '44a834', '44a226', '45a01d', '459d13' ],
	[ '18287d', '1b3977', '1f4373', '23526e', '265f6a', '229f43', '229a36', '229423', '229017', '228c08' ],
	[ '1b2664', '1f3161', '203a5f', '23435c', '254b58', '009247', '008d37', '008623', '018112', '007c00' ]
];

$colors2 = [ [ 245, 0, 0, ], [ 230, 231, 0 ], [ 20, 20, 240 ], [ 20, 240, 20 ] ];
$colorsR = [];
for ( $i = 0; $i < $dims; $i ++ ) {
	$lc = colorsBeetWin( $colors2[0], $colors2[2], $i, $dims );
	$rc = colorsBeetWin( $colors2[1], $colors2[3], $i, $dims );
	for ( $j = 0; $j < $dims; $j ++ ) {
		$c                   = colorsBeetWin( $lc, $rc, $j, $dims );
		$colorsR[ $i ][ $j ] = 'rgb(' . intval( $c[0] ) . ',' . intval( $c[1] ) . ',' . intval( $c[2] ) . ')';
	}
}
?>

<div class="container">

    <div class="header">Hi, haw do you feel today?</div>
    <div id="table-wrapper">
        <table id="table_meter">

			<?php

			$energyMax = count( $colorsR );
			foreach ( $colorsR as $energy => $values ) {
				echo '<tr>';
				foreach ( $values as $filing => $color ) {
					$energyVal = $energyMax - $energy - 1;
					echo "<td style='background-color: $color;' class='td_meter' >
                            <div class='div_meter' data-pleasantness='$filing' data-energy='$energyVal' style='background-color: $color;' ></div>
                        </td>\n";
				}
				echo '</tr>';
			}
			?>
        </table>

        <div id="x-axes"
             style="position:absolute;height:4px; width: 100%;background-color: #fff; bottom:50%; left:0;"></div>
        <div id="y-axes"
             style="position:absolute;height:100%; width: 4px;background-color: #fff; top:0; right:50%"></div>
        <div id="bad-title" style="position: absolute; top:51%; left:1%; font-size:25px; color:#fff;">Bad</div>
        <div id="good-title" style="position: absolute; top:51%; right:1%; font-size:25px; color:#fff;">Good</div>
        <div id="high-title" style="position: absolute; top:1%; left:51%; font-size:25px; color:#fff;">High Energy</div>
        <div id="low-title" style="position: absolute; bottom:1%; left:51%; font-size:25px; color:#fff;">Low Energy</div>
    </div>
    <div tabindex="-1" role="dialog" id="countdown_modal_wrapper">
        <div class="fade"></div>
        <div tabindex="-1" id="countdown_modal">

            <div class="modal-content">
				<?php if ( $building['topText'] ) { ?>
                    <div class="modal-header">
                        <h4 class="modal-title"><?= $building['topText'] ?></h4>
                    </div>
				<?php } ?>
                <div class="modal-body" style="text-align: center;position: relative">
                    <div id="div_countdown"></div>

                </div>
				<?php if ( $building['bottomText'] ) { ?>
                    <div class="modal-footer">
						<?= $building['bottomText'] ?>
                    </div>
				<?php } ?>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.modal -->

</div>
<img src="/web/images/ripple.gif" class="check_icon" id="check_icon" >
<?php $this->endBody() ?>
</body>
<script>
    $(document).ready(function() {
        var bid      = "<?= $building['id'] ?>";
        var busy     = {ajax: false, anim: false};
        $("#countdown_modal").click(function(e) {
            e.preventDefault();
            $("#countdown_modal").modal("show");
            return false;
        });
        /**
         *
         */
        function resize() {
            var hd = window.innerHeight;
            $(".container").css({height: hd + "px"});
            hd = "" + (hd - 50) / <?= $dims ?> +"px";
            wd = "" + 100 / <?= $dims ?> +"%";
            $(".td_meter").css({width: wd, height: hd});
        }

        resize();

        $(window).resize(function() {
            resize();
        });


        function clearMeter() {
            if (busy.ajax || busy.anim) return;
            $("#countdown_modal_wrapper").fadeOut("slow");
            $('#check_icon').hide();
        }

        $("#table_meter").on("click", ".div_meter", function(e) {
            if (busy.ajax || busy.anim) return;
            busy.anim   = true;
            var that    = $(this);
            var data    = {
                bid:          bid,
                energy:       that.attr("data-energy") * 1 - 10,
                pleasantness: that.attr("data-pleasantness") * 1 - 10
            };
            busy.ajax   = true;
            var posting = $.post("/meter", data);
            posting.done(function() {
                busy.ajax = false;
                clearMeter(that);
            });

            var x = e.clientX;
            var y = e.clientY;
            var icon= $('#check_icon');
            icon.css({top: (y - icon.height()/2)+'px', left: (x - icon.width()/2)+'px'}).show();
            var counter1 = window.setInterval(function() {
                $("#countdown_modal_wrapper").fadeIn("slow");
                var cnt       = 2;
                var countDown = $("#div_countdown");
                countDown.text(cnt);
                countDown.css({fontSize: $("#table_meter").innerHeight() * 0.5 + "px"});
                var counter = window.setInterval(function() {
                    cnt--;
                    if (cnt > -1) {
                        countDown.text(cnt);
                        return;
                    }
                    clearInterval(counter);
                    busy.anim = false;
                    clearMeter(that);
                }, 700);

                clearInterval(counter1);
            },500);


        });
    });
</script>
<style>
    body {
        background-color: #111;
    }

    .container {
        width: 100%;
        position: relative;
        top: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }


    .td_meter {
        width: <?= $size ?>px;
        height: <?= $size ?>px;
        cursor: pointer;
        position: relative;

    }

    .header {
        height: 50px;
        color: #fff;
        font-size: 40px;
        text-align: center;
    }

    .div_meter {
        opacity: 1;
        background-color: #444;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        position: absolute;
        z-index: auto;
    }

    img.check_icon {
        max-width: 15%;
        max-height:15%;
        position: absolute;
        display: none;
        z-index: 6;
    }

    table, #table-wrapper {
        margin: 0 auto;
        width: 100%;
    }

    #table-wrapper {
        position: absolute;
        bottom:0;
    }

    #div_countdown {
        font-size: 25em;
        line-height: 1;

    }

    #countdown_modal {
        text-align: center;
        position: relative;
        margin: 10% auto 9%;
        opacity: 1;
    }

    #countdown_modal_wrapper {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 7;
        background-color: hsla(50, -18%, 13%, 0.5);
        opacity: 1;
        height: 100%;
        width: 100%;
        display: none;
    }

    @media screen and (min-width: 768px) {
        #countdown_modal:before {
            display: inline-block;
            vertical-align: middle;
            content: " ";
            height: 100%;
        }
    }

    .modal-content {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
        width: 50%;
        margin: 0 auto;

    }

    #countdown_modal h4, .modal-footer {
        text-align: center;
    }

    .modal-backdrop {
        display: none;
    }

    .modal-content {
        background-color: #000;
        color: #fff;
    }

    .modal-header {
        border-bottom: 1px solid #383838;
    }

    .modal-footer {
        border-top: 1px solid #383838;
    }
</style>
</html>
<?php $this->endPage() ?>
