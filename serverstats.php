<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include "inc_core/head.php";
            include './includes/config.php';
            include './includes/functions.php';
        ?>
        <title>CatEye</title>
        <?php  if(@$_GET['refresh']>0){ ?>
            <meta http-equiv="refresh" content="<?=$_GET['refresh']*60?>;URL='<?php echo $_SERVER['PHP_SELF']?>'">
        <?php } ?>   
        <?php
            $sortby=" order by timestart desc ";
            if(isset($_GET['sortby'])){
            	$s = explode("~",$_GET['sortby']);
            	$sortby = " order by ".$s[0]." ".$s[1].", timestart desc";
            }
        ?>
    </head>
    <body>
        <div class="dashboard-pages sql-page">
            <?php include "inc_core/header.php"; ?> 
            <div class="dashboard-body-wrapp clearfix">
                <?php include "inc_core/left_sidebar.php"; ?> 
                <div class="dashboad-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dashboard-body-main">
                                <div class="row">
                                   <div class="col-xs-12">
                                       <h3 class="main_title">Manage Insances</h3>
                                       <div class="instance_box">
                                            <table class="table table-bordered table-gradient log-errors-table">
                                                <?php 
                                                    $sSQL="SELECT * FROM ce_managed_instances";
                                                    $result = sql($sSQL, $dbh);    
                                                ?>
                                                <th>Name</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Date Time</th>
                                                <tbody>
                                                    <?php 
                                                    if(count($result)>0){
                                                    foreach ($result as $row) {                                                   
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <?= $row['name'] ?>
                                                            </td>
                                                            <td>
                                                                <?= $row['title'] ?>
                                                            </td>
                                                            <td>
                                                                <?= $row['status'] ?>
                                                                <?php 
                                                                if(strtolower($row['status'])=='running')
                                                                    echo '<i class="fa fa-arrow-circle-up text-success" aria-hidden="true"></i>';
                                                                else if(strtolower($row['status'])=='stopped')
                                                                    echo '<i class="fa fa-arrow-circle-down text-danger" aria-hidden="true"></i>';
                                                                else if(strtolower($row['status'])=='starting')
                                                                    echo '<i class="fa fa-arrow-circle-up text-warning" aria-hidden="true"></i>';
                                                                else if(strtolower($row['status'])=='stopping')
                                                                    echo '<i class="fa fa-arrow-circle-down text-warning" aria-hidden="true"></i>';
                                                                else if(strtolower($row['status'])=='undetermined')
                                                                    echo '<i class="fa fa-question-circle text-warning" aria-hidden="true"></i>';
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?= $row['created_at'] ?>
                                                            </td>
<!--                                                            <td>
                                                                <button type="" class="btn btn btn-icon fa fa-check"></button>
                                                                <button type="" class="btn btn btn-icon fa fa-times"></button>
                                                            </td>-->
                                                        </tr>      
                                                    <?php } } ?>    
                                                </tbody>
                                            </table>
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dashboard-body-main">
                                <div class="row">
                                   <div class="col-xs-12">
                                        <h3 class="main_title">States</h3>
                                       <div class="instance_box">
                                            <img src="http://via.placeholder.com/813x271" class="img-responsive wid100">
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="dashboard-body-main">
                                <div class="dashboard-body-functions clearfix">
                                    <form method="get" class="pull-left">
                                        <div class="search_box">
                                            <label>Search/Filter:</label>
                                            <input type="text" class="form-control" name="search" value="<?=@$_GET['search']?>">
                                        </div>
                                        <div class="time_period_box">
                                            <label>Time Period:</label>
                                            <div id="reportrange" class="form-control dates">
                                                <i class="fa fa-calendar"></i><span></span><i class="fa fa-caret-down"></i>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="btn-group pull-right refresh-btn">
                                        <button type="button" class="btn btn-refresh">
                                            <a href="./database.php"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                        </button>
                                        <button type="button btn-default" class="btn dropdown-toggle btn-refresh" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>

                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="./database.php">Manual</a></li>
                                            <li><a href="./database.php?refresh=5">Every 5 Minutes</a></li>
                                            <li><a href="./database.php?refresh=15">Every 15 Minutes</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h3 class="main_title space20">States</h3>

                                <div class="vertical_filter_box">
                                    <ul class="list-inline">
                                        <li><a href="#detail_modal" data-toggle="modal" data-target="#detail_modal">Environment <span class="fa fa-filter"></span></a></li>
                                    </ul>
                                </div>

                                <div class="row space20">
                                    <div class="col-xs-12">
                                        <div class="server_tab_box">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#Perfomance" aria-controls="Perfomance" role="tab" data-toggle="tab">Perfomance</a></li>
                                                <li role="presentation"><a href="#AppErrors" aria-controls="AppErrors" role="tab" data-toggle="tab">App Errors</a></li>
                                                <li role="presentation"><a href="#SystemErrors" aria-controls="SystemErrors" role="tab" data-toggle="tab">System Errors</a></li>
                                                <li role="presentation"><a href="#Timeout" aria-controls="Timeout" role="tab" data-toggle="tab">Timeout</a></li>
                                                <li role="presentation"><a href="#Volume" aria-controls="Volume" role="tab" data-toggle="tab">Volume</a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="Perfomance">
                                                    <div id="average_time"></div>
                                                    <style type="text/css">
                                                        #average_time {
                                                            width   : 100%;
                                                            height  : 500px;
                                                        }
                                                    </style>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="AppErrors">
                                                    <div id="application_errors"></div>
                                                    <style type="text/css">
                                                        #application_errors {
                                                            width   : 100%;
                                                            height  : 500px;
                                                        }
                                                    </style>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="SystemErrors">
                                                    <div id="system_errors"></div>
                                                    <style type="text/css">
                                                        #system_errors {
                                                            width   : 100%;
                                                            height  : 500px;
                                                        }
                                                    </style>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="Timeout">
                                                    <div id="timeout_errors"></div>
                                                    <style type="text/css">
                                                        #timeout_errors {
                                                            width   : 100%;
                                                            height  : 500px;
                                                        }
                                                    </style>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="Volume">
                                                    <div id="total_invocations"></div>
                                                    <style type="text/css">
                                                        #total_invocations {
                                                            width   : 100%;
                                                            height  : 500px;
                                                        }
                                                    </style>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    

                    
                </div>
            </div>
            <div class="dashboard-header dashboard-footer">
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade transaction_detail_modal" id="detail_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Filter By</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="sch_title">EnterPrise</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        <div class="checkbox">
                                        <?php      
                                            $sql = 'SELECT DISTINCT web_server_instance_name FROM ce_callobject_stats';
                                            mysql_select_db('cateyedashboard_db');
                                            $retval = mysql_query( $sql, $dbh );
                                            while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
                                            {
                                                ?>
                                                <input type="checkbox" name="" id="EnterPrise1">
                                                <?php
                                                echo "{$row['web_server_instance_name']}"."<br>";
                                            }
                                        ?>
                                        </div>
                                    </li> 
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <h3 class="sch_title">Webservers</h3>
                                <ul class="list-unstyled">
                                    <li>
                                        <div class="checkbox">
                                        <?php      
                                            $sql = 'SELECT DISTINCT enterprise_server FROM ce_callobject_stats';
                                            mysql_select_db('cateyedashboard_db');
                                            $retval = mysql_query( $sql, $dbh );
                                            while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
                                            {?>
                                                <input type="checkbox" name="" id="Webserver1"><?php
                                                echo "{$row['enterprise_server']}";
                                            }
                                        ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center space30">
                                <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "inc_core/footer_scripts.php"; ?> 

        <script type="text/javascript">        
        function detail_modal(id)
        {            
        console.log(id);
//            $('#detail_modal').on('shown.bs.modal', function () {
//            	console.log('ajax/transactions_details.php?id='+id);
//                $('#detail_modal .modal-body').load('ajax/transactions_details.php?id='+id);
//            })
            $.post("ajax/transactions_details.php?id="+id, function(data, status){
                      $('#detail_modal .modal-body').html(data);
             });
        }
    </script>

    <!-- https://www.amcharts.com/demos/multiple-value-axes/?theme=dark -->
    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- Chart code -->
    <script>
    var tabs = ["average_time", "application_errors","system_errors","timeout_errors","total_invocations"];    
    tabs.forEach(function(entry) {         
    
    var chartData = generateChartData(entry);    
    var chart = AmCharts.makeChart(entry, 
    {
        "type": "serial",
        "theme": "light",
        "legend": {
            "useGraphSettings": true
        },
        "dataProvider": chartData,
        "synchronizeGrid":true,
        "valueAxes": [{
            "id":"v1",
            "axisColor": "#FF6600",
            "axisThickness": 2,
            "axisAlpha": 1,
            "position": "left"
        }, {
            "id":"v2",
            "axisColor": "#FCD202",
            "axisThickness": 2,
            "axisAlpha": 1,
            "position": "right"
        }, {
            "id":"v3",
            "axisColor": "#B0DE09",
            "axisThickness": 2,
            "gridAlpha": 0,
            "offset": 50,
            "axisAlpha": 1,
            "position": "left"
        }],
        "graphs": [{
            "valueAxis": "v1",
            "lineColor": "#FF6600",
            "bullet": "round",
            "bulletBorderThickness": 1,
            "hideBulletsCount": 30,
            "title": "red line",
            "valueField": "visits",
            "fillAlphas": 0
        }, {
            "valueAxis": "v2",
            "lineColor": "#FCD202",
            "bullet": "square",
            "bulletBorderThickness": 1,
            "hideBulletsCount": 30,
            "title": "yellow line",
            "valueField": "hits",
            "fillAlphas": 0
        }, {
            "valueAxis": "v3",
            "lineColor": "#B0DE09",
            "bullet": "triangleUp",
            "bulletBorderThickness": 1,
            "hideBulletsCount": 30,
            "title": "green line",
            "valueField": "views",
            "fillAlphas": 0
        }],
        "chartScrollbar": {},
        "chartCursor": {
            "cursorPosition": "mouse"
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "axisColor": "#DADADA",
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true,
            "position": "bottom-right"
         }
    });    
    });
    chart.addListener("dataUpdated", zoomChart);
    zoomChart();

    // generate some random data, quite different range

    function generateChartData(key)
    {        
        var chartData = [];
        var firstDate = new Date();
        firstDate.setDate(firstDate.getDate() - 10);

            var visits = 0;
            var hits = 2900;
            var views = 8700; 
        for (var i = 0; i < 50; i++) 
        {
            // we create date objects here. In your data, you can have date strings
            // and then set format of your dates using chart.dataDateFormat property,
            // however when possible, use date objects, as this will speed up chart rendering.
            var newDate = new Date(firstDate);
            newDate.setDate(newDate.getDate() + i);
            $.ajax({
                type: "GET",
                async: false,
                url: 'ajax/serverstats_garphdata.php?date='+newDate.getFullYear()+"-"+newDate.getMonth()+"-"+newDate.getDate()+'&key='+key+'&search=<?php echo @$_GET['search']?>',
                success: function (str) 
                {
                    visits=str;
                }
            });
            //visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
            hits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
            views += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

            chartData.push({
                date: newDate,
                visits: visits,
               // hits: hits,
               // views: views
            });
        }
        return chartData;
    }

    function zoomChart()
    {
        chart.zoomToIndexes(chart.dataProvider.length - 20, chart.dataProvider.length - 1);
    }
    </script>sassdsd
    </body>
</html>

