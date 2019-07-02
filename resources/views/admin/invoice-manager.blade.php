@extends('admin.layout')

@section('webpage_title', 'Invoice Manager')

@section('webpage_header')
<h1>
    Invoice Manager
</h1>
<ol class="breadcrumb">
    <li><a href="<?php echo \Sinevia\Business\Helpers\Links::adminHome(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>">Accounting</a></li>
    <li class="active"><a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>">Invoices</a></li>
</ol>
@stop

@section('webpage_content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(function () {
        $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>


@include('business::shared.navigation')

<div class="box box-primary">
    <div class="box-header with-border">
        <!-- START: Filter -->
        <div class="well hidden-sm hidden-xs">
            <form class="form-inline" name="form_filter" method="get" style="margin:0px;">
                Filter:
                <div class="form-group">
                    <label class="sr-only" for="filter_id">ID:</label>
                    <input type="text" id="filter_id" name="filter_id" class="form-control" value="<?php echo htmlentities($filterId); ?>" placeholder="ID / reference">
                </div>

                <div class="input-group" style="width:180px;">
                    <input name="filter_starts" type="text" class="form-control datepicker" value="<?php echo htmlentities(substr($filterStarts, 0, 10)); ?>" style="font-size: 11px;" placeholder="start" />
                    <span class="input-group-addon" style="padding:6px 3px;">:</span>
                    <input name="filter_ends" type="text" class="form-control datepicker" value="<?php echo htmlentities(substr($filterEnds, 0, 10)); ?>" style="font-size: 11px;" placeholder="end" />
                </div>

                <select class="form-control" name="filter_status">
                    <option value="">- status -</option>
                    <?php foreach ($statusList as $key => $value) { ?>
                        <?php $selected = ($filterStatus == $key) ? 'selected="selected"' : ''; ?>
                        <option value="<?php echo $key ?>"  <?php echo $selected ?>>
                            <?php echo $value ?>
                        </option>
                    <?php } ?>
                </select>

                <button class="btn btn-primary">
                    <span class="fa fa-search"></span>
                </button>

                <button type="button" class="btn btn-primary pull-right" onclick="showInvoiceCreateDialog();">
                    <span class="fa fa-plus-circle"></span>
                    Add Invoice
                </button>
            </form>
        </div>
        <!-- END: Filter -->

    </div>

    <div class="box-body">

        <!--START: Invoices -->
        <style scoped="scoped">
            .table-striped > tbody > tr:nth-child(2n+1) > td{
                background-color: transparent !important;
            }
            .table-striped > tbody > tr:nth-child(2n+1){
                background-color: #F9F9F9 !important;
            }
            #table_invoices tr:hover {
                background-color: #FEFF8F !important;
            }
            #table_invoices td.Debit {
                color: red;
                font-weight: bold;
            }
            #table_invoices td.Credit {
                color: green;
                font-weight: bold;
            }
        </style>
        <table id="table_invoices" class="table table-striped">
            <thead>
                <tr>
                    <th style="text-align:center;">
                        <a href="?cmd=pages-manager&amp;by=CustomerId&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                            Customer&nbsp;<?php
                            if ($orderby === 'CustomerId') {
                                if ($sort == 'asc') {
                                    ?>&#8595;<?php } else { ?>&#8593;<?php
                                }
                            }
                            ?>
                        </a>
                    </th>
                    <th style="text-align:center;width:100px;">
                        Issued On
                    </th>
                    <th style="text-align:center;width:100px;">
                        Status
                    </th>
                    <th style="text-align:center;width:100px;">
                        Amount
                    </th>
                    <th style="text-align:center;width:160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($invoices as $invoice) { ?>
                    <?php
                    $id = $invoice->Id;
                    $status = $invoice->Status;
                    $customerClass = config('business.models.customer');
                    $customerInstance = new $customerClass;
                    $customer = Sinevia\Business\Helpers\Helper::customerInstance()->find($invoice->CustomerId);
                    $customerName = is_null($customer) ? 'Unknown' : $customer->FirstName . ' ' . $customer->LastName;
                    $issuedOn = is_null($invoice->IssuedOn) ? date('d M Y', strtotime($invoice->CreatedAt)) : date('d M Y', strtotime($invoice->IssuedOn));
                    ?>
                    <tr>
                        <td style="text-align:left;vertical-align: middle;">
                            <a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceUpdate(['InvoiceId' => $invoice['Id']]); ?>" style="font-size:14px;">
                                <b><?php echo $customerName; ?></b>
                            </a>
                            <br />
                            <div style="color:#999;font-size: 10px;">
                                Reference: <?php echo $invoice->Reference; ?>
                            </div>
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            <?php echo str_replace(' ', '&nbsp;', $issuedOn); ?><br>
                        </td>
                        <td class="<?php echo $status; ?>" style="text-align:center;vertical-align: middle;">
                            <?php echo $status; ?><br>
                        </td>
                        <td style="text-align:right;vertical-align: middle;">
                            <?php echo money_format('%i', $invoice->Total); ?><br>
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            <a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceUpdate(['InvoiceId' => $invoice['Id']]); ?>" class="btn btn-sm btn-warning">
                                <span class="glyphicon glyphicon-edit"></span>
                                Edit
                            </a>

                            <?php if ($invoice->Status == 'Deleted') { ?>
                                <button class="btn btn-sm btn-danger" onclick="confirmInvoiceDelete('<?php echo $invoice->Id; ?>');">
                                    <span class="glyphicon glyphicon-remove-sign"></span>
                                    Delete
                                </button>
                            <?php } ?>

                            <?php if ($invoice->Status != 'Deleted') { ?>
                                <button class="btn btn-sm btn-danger" onclick="confirmInvoiceMoveToTrash('<?php echo $invoice->Id; ?>');">
                                    <span class="glyphicon glyphicon-trash"></span>
                                    Trash
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- END: Invoices -->

        <!-- START: Pagination -->
        {!! $invoices->render() !!}
        <!-- END: Pagination -->
    </div>

</div>

@include('business::admin/invoice-create-modal')

@include('business::admin/invoice-delete-modal')

@stop