@extends('admin.layout')

@section('webpage_title', 'Finances')

@section('webpage_header')
<section class="content-header">
    <h1>
        Finances
        <small>Analytics</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo \Sinevia\Business\Helpers\Links::adminHome(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
</section>
@stop

@section('webpage_content')

    @include('business::shared.navigation')

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(function () {
        $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>

<!-- START: Box Form -->
<div class="box box-primary">
    <div class="box-header with-border">
        <!--     START: Filter -->
        <div class="well" style="display:table;width:100%;">
            <form class="form-inline" name="form_filter" method="get" style="margin:0px;">
                <div class="col-sm-4 col-md-3" style="display:table;min-width: 280px;">
                    <input class="form-control datepicker" name="filter_from" value="<?php echo htmlentities($filterFrom); ?>" style="width:105px;float:left;" />
                    <span style="float:left;margin:4px 10px;">to</span>
                    <input class="form-control datepicker" name="filter_to" value="<?php echo htmlentities($filterTo); ?>" style="width:105px;float:left" />
                </div>
                <div class="col-md-3" class="form-group">
                    <select class="form-control" name="filter_customer_id" style="max-width:200px;" onchange="form_filter.submit()">
                        <option value="">- customer -</option>
                        <?php foreach ($customerList as $c) { ?>
                            <?php $selected = $c->Id == $filterCustomerId ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $c->Id; ?>" <?php echo $selected ?>>
                                <?php echo htmlentities($c->FirstName); ?>
                                <?php echo htmlentities($c->LastName); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1" style="display:table;">
                    <button class="btn btn-primary">
                        <span class="glyphicon glyphicon-search"></span>
                        Filter
                    </button>
                </div>
            </form>
        </div>
        <!--     END: Filter -->
    </div>
    <div class="box-body no-padding">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <div id="barchart_values" style="width: 100%; height: 500px;"></div>
    </div>
</div>
<!-- END: Box Form -->


<!-- START: Scripts -->
<script>
var invoiceData = <?php echo json_encode($credit); ?>;
var debitData = <?php echo json_encode($debit); ?>;
function  drawGraph(invoiceData) {
    console.log(invoiceData);
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var options = [[
                'Business', 'Credit (Income)', 'Debit (Expences)'
            ]];
        var sortedKeys = Object.keys(invoiceData).sort();
        sortedKeys.forEach(function (key) {
            //console.log(key);
            options[options.length] = [
                key,
                invoiceData[key],
                debitData[key],
            ];
        });

        var data = google.visualization.arrayToDataTable(options);

        var options = {
            title: 'Balance Per Month',
            //width: 600,
            //height: 400,
            legend: {position: 'top', maxLines: 3},
            bar: {groupWidth: '99%'},
            isStacked: false
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("barchart_values"));
        chart.draw(data, options);
    }
}

/**
    * After page is loaded
    * @returns {void}
    */
$(function () {
    drawGraph(invoiceData);
});
</script>
<!-- END: Scripts -->
@stop