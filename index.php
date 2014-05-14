<?php

include 'bibot.conf.php';

$a_date = array();
// kraken
$a_kraken = array();
$HOW = 'a';
$filePath = $dir.$DS.$HOW.'.csv';
foreach (file($filePath) as $k=>$l) {
    $data_ss = explode(';',$l);
    $date = date('Y-m-d H:i', $data_ss[0]);
    $a_kraken[] = $data_ss[1];
    $a_date[] = $date;
}

// Bitcurex
$a_bitcurex = array();

$filePath = $dir.$DS.$HOW.'.bitcurex.csv';
foreach (file($filePath) as $k=>$l) {
    $data_ss = explode(';',$l);
    $a_bitcurex[] = $data_ss[1];
}

/* shit code */
// Kraken
$HOW = 'b';
$n_kraken = array();
$filePath = $dir.$DS.$HOW.'.csv';
foreach (file($filePath) as $k=>$l) {
    $data_ss = explode(';',$l);
    $b_kraken[] = $data_ss[1];
}
// Bitcurex
$b_bitcurex = array();
$filePath = $dir.$DS.$HOW.'.bitcurex.csv';
foreach (file($filePath) as $k=>$l) {
    $data_ss = explode(';',$l);
    $b_bitcurex[] = $data_ss[1];
}
/* shit code */


foreach ($a_kraken as $k=>$v) {
    $BUFFER .= "[ '" . $a_date[$k] . "'," . $a_kraken[$k] . "," . $a_bitcurex[$k] . "," . $b_kraken[$k] . "," . $b_bitcurex[$k] . "],";
}

?>
<html>
<head>
    <title>bibot show graph</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Data','KrakenA','BitcurexA','KrakenB','BitcurexB'],
            <? echo substr( $BUFFER, 0, -1 ); ?>
        ]);
        var options = {
            title: 'BitcoinTicker',
            hAxis: { title: "Hours", gridlines:{color:'#ddd',count:48} },
            vAxes: {
                0: {logScale: false, title:"EUR", minorGridlines:{color:'#eee',count:4} }//,
                //1: {logScale: false, title:"Kraken", minorGridlines:{color:'#eee',count:4} }
            },
            series:{
                0:{targetAxisIndex:0},
                1:{targetAxisIndex:0}
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 1200px; height: 500px;"></div>
</body>