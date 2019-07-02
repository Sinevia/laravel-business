@extends('admin.layout')

@section('webpage_title', 'Edit Invoice')

@section('webpage_header')
<section class="content-header">
    <h1>
        Edit Invoice: Ref. <?php echo $reference; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo \Sinevia\Business\Helpers\Links::adminHome(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>">Invoices</a></li>
        <li class="active">Edit Invoice</li>
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

<div class="box box-info">
    <div class="box-header">
        <div>
            <a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>" class="btn btn-info">
                <span class="glyphicon glyphicon-chevron-left"></span>
                Cancel
            </a>

            <a href="<?php echo Sinevia\Business\Helpers\Links::customerInvoiceView(['InvoiceId' => $invoice->Id]) ?>" target="_blank" class="btn btn-info pull-right" style="margin:0px 10px;">
                <i class="fa fa-eye"></i>
                Preview / Print
            </a>

            <!--
            <a href="whatsapp://send?text=<?php echo urlencode(Sinevia\Business\Helpers\Links::customerInvoiceView(['InvoiceId' => $invoice->Id])); ?>" target="_blank" class="btn btn-orange pull-right" style="margin:0px 10px;">
                <i class="fa fa-share"></i>
                Send via WhatsApp
            </a>
            -->


            <button type="button" class="btn btn-success pull-right" style="margin:0px 10px;"  onclick="$('#form_action').val('save-and-exit');
                    FORM_INVOICE_EDIT.submit();">
                <span class="glyphicon glyphicon-floppy-saved"></span>
                Save
            </button>

            <button type="button" class="btn btn-success pull-right" style="margin:0px 10px;" onclick="$('#form_action').val('save');
                    FORM_INVOICE_EDIT.submit();">
                <span class="glyphicon glyphicon-floppy-save"></span>
                Apply
            </button>
        </div>
    </div>

    <div class="box-body">

        <form name="FORM_INVOICE_EDIT" action="" method="post">
            <div class="row">
                <div class="col-sm-4">
                    <!-- START: Status -->
                    <div class="form-group">
                        <label>
                            Status
                        </label>
                        <select class="form-control" name="Status">
                            <option value=""></option>
                            <?php foreach ($statusList as $key => $value) { ?>
                                <?php $selected = ($status == $key) ? 'selected="selected"' : ''; ?>
                                <option value="<?php echo $key ?>"  <?php echo $selected ?>>
                                    <?php echo $value ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- END: Status -->
                </div>
                <div class="col-sm-4">
                    <!-- START: Customer -->
                    <div class="form-group">
                        <label>
                            Customer
                        </label>
                        <select class="form-control" name="CustomerId">
                            <option value=""></option>
                            <?php foreach ($customerList as $c) { ?>
                                <?php $selected = ($customerId == $c->Id) ? 'selected="selected"' : ''; ?>
                                <option value="<?php echo $c->Id ?>"  <?php echo $selected ?>>
                                    <?php echo $c->FirstName ?> <?php echo $c->LastName ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- END: Customer -->
                </div>
                <div class="col-sm-4">
                    <!-- START: Currency -->
                    <div class="form-group">
                        <label>
                            Currency
                        </label>
                        <select class="form-control" name="Currency">
                            <option value="">Select Currency</option>
                            <option value="GBP" <?php if ($currency == 'GBP') { ?> selected <?php } ?> >GBP</option>
                            <option value="EUR" <?php if ($currency == 'EUR') { ?> selected <?php } ?> >EUR</option>
                            <option value="USD" <?php if ($currency == 'USD') { ?> selected <?php } ?> >USD</option>
                        </select>
                    </div>
                    <!-- END: Currency -->
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label>
                        Issued Date (yyyy-dd-mm)
                    </label>
                    <input class="form-control datepicker" name="IssuedOn" type="text" value="<?php echo htmlentities($issuedOn); ?>"/>
                </div>
                <div class="col-sm-4">
                    <label>
                        Due Date (yyyy-dd-mm)
                    </label>
                    <input class="form-control datepicker" name="DueOn" type="text" value="<?php echo htmlentities($dueOn); ?>"/>
                </div>
                <div class="col-sm-4">
                    <label>
                        Paid Date (yyyy-dd-mm)
                    </label>
                    <input class="form-control datepicker" name="PaidOn" type="text" value="<?php echo htmlentities($paidOn); ?>"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label>
                        Reference
                    </label>
                    <input class="form-control" name="Reference" type="text" value="<?php echo htmlentities($reference); ?>"/>
                </div>
                <div class="col-sm-4">
                    <label>
                        Transaction
                    </label>
                    <input class="form-control" name="TransactionId" type="text" value="<?php echo htmlentities($transactionId); ?>"/>
                </div>
            </div>

            <br />

            <div class="form-group">
                <label>
                    Details
                </label>
                <style scoped="scoped">
                    #tableItemsList input.unit_price{
                        text-align: right;
                    }
                    #tableItemsList input.quantity{
                        text-align: right;
                    }
                    #tableItemsList input.discount{
                        text-align: right;
                    }
                    #tableItemsList input.subtotal{
                        text-align: right;
                    }
                    #tableItemsList input.total{
                        text-align: right;
                    }
                </style>

                <table id="tableItemsList" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:1px;">#</th>
                            <th>Services</th>
                            <th style="width:120px;text-align: center;">Units</th>
                            <th style="width:120px;text-align: center;">Price</th>
                            <th style="width:120px;text-align: center;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoiceItemList as $item) { ?>
                            <?php
                            $itemId = $item['Id'];
                            ?>
                            <tr class="item" id="tr_<?php echo $item['Id']; ?>">
                                <td>
                                    <input type="hidden" name="Items['<?php echo $itemId; ?>'][Id]" class="form-control id" value="<?php echo $itemId; ?>" style="width:100px;">
                                    <button type="button" class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); tableItemsListRefresh();">
                                        &times;
                                    </button>
                                </td>
                                <td class="description">
                                    <textarea name="Items['<?php echo $itemId; ?>'][Details]" class="form-control description" onblur="tableItemsListRefresh();"><?php echo $item['Details']; ?></textarea>
                                </td>
                                <td class="quantity">
                                    <input  name="Items['<?php echo $itemId; ?>'][Units]" class="form-control quantity" value="<?php echo $item['Units']; ?>"  onblur="tableItemsListRefresh();">
                                </td>
                                <td class="unit_price">
                                    <input name="Items['<?php echo $itemId; ?>'][PricePerUnit]" class="form-control unit_price" onblur="tableItemsListRefresh();" value="<?php echo $item['PricePerUnit']; ?>">
                                </td>
                                <td class="subtotal">
                                    <input  name="Items['<?php echo $itemId; ?>'][Total]" class="form-control subtotal" onblur="tableItemsListRefresh();" value="<?php echo $item['Total']; ?>" readonly>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button type="button" onclick="itemAdd();" class="btn btn-info btn-xs">
                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                    Add Item
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-left" style="background:#F9F9F9;">
                                <b>Discount description (optional)</b>
                                <textarea class="form-control discount" name="DiscountDescription" onblur="tableItemsListRefresh()"><?php echo htmlentities($discountDescription); ?></textarea>
                            </td>
                            <td colspan="1" valign="top" style="background:#F9F9F9;">
                                <b>Discount Subtotal</b>
                                <input class="form-control discount" name="Discount" onblur="tableItemsListRefresh()" value="<?php echo htmlentities($discount); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">
                                Subtotal
                            </td>
                            <td colspan="1">
                                <input class="form-control total" name="Subtotal" readonly value="<?php echo htmlentities($subtotal); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">
                                Tax (%)
                            </td>
                            <td colspan="1">
                                <input class="form-control total" name="Tax" onblur="tableItemsListRefresh()" value="<?php echo htmlentities($tax); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">
                                Total
                            </td>
                            <td colspan="1">
                                <input class="form-control total" name="Total" class="text-right" readonly value="<?php echo htmlentities($total); ?>">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <hr />

            <div class="form-group">
                <label>
                    Administrative Notes
                </label>
                <textarea class="form-control" name="Memo" ><?php echo $invoice['Memo']; ?></textarea>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="action" id="form_action" value="save-and-exit">
        </form>

    </div>

    <div class="box box-footer">
        <a href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>" class="btn btn-info">
            <span class="glyphicon glyphicon-chevron-left"></span>
            Cancel
        </a>

        <button type="button" class="btn btn-success pull-right" style="margin:0px 10px;"  onclick="$('#form_action').val('save-and-exit');
                FORM_INVOICE_EDIT.submit();">
            <span class="glyphicon glyphicon-floppy-saved"></span>
            Save
        </button>

        <button type="button" id="ButtonApply" class="btn btn-success pull-right" style="margin:0px 10px;" onclick="$('#form_action').val('save');
                FORM_INVOICE_EDIT.submit();">
            <span class="glyphicon glyphicon-floppy-save"></span>
            Apply
        </button>
    </div>


</div>

<script>
    /**
     * Formats money with specified thousands and decimal separator. Default 1,000,000.00
     * @param {int} decPlaces
     * @param {String} thouSeparator
     * @param {String} decSeparator
     * @returns {String}
     */
    Number.prototype.formatMoney = function (decPlaces, thouSeparator, decSeparator) {
        var n = this;
        var decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces;
        var decSeparator = decSeparator === undefined ? "." : decSeparator;
        var thouSeparator = thouSeparator === undefined ? "," : thouSeparator;
        var sign = n < 0 ? "-" : "";
        var i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "";
        var j = (j = i.length) > 3 ? j % 3 : 0;
        return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
    };

    function itemAdd() {
        var uuid = guid();
        var tableRow = '<tr class="item">';
        tableRow += '<td class="options">';
        tableRow += ' <button class="btn btn-xs" onclick="$(this).closest(\'tr\').remove();tableItemsListRefresh();"><span class="glyphicon glyphicon-remove"></span></button>';
        tableRow += ' <input type="hidden" name="Items[' + uuid + '][Id]" class="form-control id" value="' + uuid + '">';
        tableRow += '</td>';
        tableRow += '<td class="description">';
        tableRow += ' <textarea name="Items[' + uuid + '][Details]" class="form-control description" onblur="tableItemsListRefresh();"></textarea>';
        tableRow += '</td>';
        tableRow += '<td class="quantity">';
        tableRow += ' <input name="Items[' + uuid + '][Units]" class="form-control quantity" onblur="tableItemsListRefresh();" value="">';
        tableRow += '</td>';
        tableRow += '<td class="unit_price">';
        tableRow += ' <input name="Items[' + uuid + '][PricePerUnit]" class="form-control unit_price" onblur="tableItemsListRefresh();" value="">';
        tableRow += '</td>';
        tableRow += '<td class="subtotal">';
        tableRow += ' <input name="Items[' + uuid + '][Total]" class="form-control subtotal" onblur="tableItemsListRefresh();" value="" readonly>';
        tableRow += '</td>';
        tableRow += '</tr>';
        $('#tableItemsList tbody').append(tableRow);
    }

    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
    }

    function tableItemsListRefresh() {
        var tax = $.trim($('#tableItemsList input[name="Tax"]').val());
        tax = parseFloat(tax);

        var discount = $.trim($('#tableItemsList input[name="Discount"]').val());
        discount = parseFloat(discount);
        $('#tableItemsList input[name="Discount"]').val('' + discount.toFixed(2));
        var subTotal = 0.00;
        var total = 0.00;

        $("#tableItemsList tbody tr.item").each(function () {
            var unitPrice = $(this).find('input.unit_price').val();
            if (unitPrice === '') { // Otherwise NaN
                unitPrice = '0.00';
            }

            unitPrice = parseFloat(unitPrice.replace(/[^0-9.]/g));
            $(this).find('input.unit_price').val('' + unitPrice.toFixed(2));
            var quantity = $(this).find('input.quantity').val();
            if (quantity === '') { // Otherwise NaN
                quantity = '0';
            }
            quantity = parseFloat(quantity.replace(/[^0-9.]/g));
            $(this).find('input.quantity').val('' + quantity.toFixed(2));
            var price = parseFloat(unitPrice) * parseFloat(quantity);
            $(this).find('input.subtotal').val(price.formatMoney(2, ',', '.'));
            subTotal += parseFloat(price);
        });

        subTotal = subTotal-discount;

        var salesTax = subTotal * (parseFloat(tax) / 100);
        total = subTotal + salesTax;
        console.log('Subtotal');
        console.log(subTotal);
        console.log('Sales Tax');
        console.log(salesTax);
        console.log('Total');
        console.log(total);
        $('#tableItemsList input[name="Subtotal"]').val(subTotal.formatMoney(2, ',', '.'));
        $('#tableItemsList input[name="Total"]').val(total.formatMoney(2, ',', '.'));
    }

    function invoiceSave(closePage) {
        var customerId = $('#InvoiceCustomerId :selected').val();
        var status = $.trim($('#formInvoiceUpdate select[name="InvoiceStatus"]').val());
        var currency = $.trim($('#formInvoiceUpdate select[name="InvoiceCurrency"]').val());
        var subtotal = $.trim($('#formInvoiceUpdate input[name="Subtotal"]').val());
        var discount = $.trim($('#formInvoiceUpdate input[name="Discount"]').val());
        var tax = $.trim($('#formInvoiceUpdate input[name="Tax"]').val());
        var total = $.trim($('#formInvoiceUpdate input[name="Total"]').val());
        var memo = $.trim($('#formInvoiceUpdate textarea[name="InvoiceMemo"]').val());
        var closePage = typeof closePage === undefined ? false : closePage;

        var items = [];

        $("#tableItemsList tbody tr.item").each(function () {
            var description = $(this).find('textarea.description').val();
            var unitPrice = $(this).find('input.unit_price').val();
            unitPrice = parseFloat(unitPrice.replace(/[^0-9.]/g));
            var unitsQuantity = $(this).find('input.quantity').val();
            unitsQuantity = parseFloat(unitsQuantity.replace(/[^0-9.]/g));
            var itemId = $(this).find('input.itemId').val();
            var itemSubtotal = parseFloat(unitPrice) * parseFloat(unitsQuantity);
            var item = {
                'itemId': itemId,
                'Description': description,
                'UnitPrice': unitPrice,
                'UnitsQty': unitsQuantity,
                'Subtotal': itemSubtotal
            };

            items[items.length] = item;
        });

        var data = {
            InvoiceId: '<?php echo $invoice['UniqueId'] ?>',
            CustomerId: customerId,
            Currency: currency,
            Status: status,
            Items: items,
            Discount: discount,
            Subtotal: subtotal,
            Tax: tax,
            Total: total,
            Memo: memo
        };
        console.log(data);
        var url = "<?php //echo Application::createActionUrl('business-invoice-save');               ?>";
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            dataType: "text",
            data: {
                data: data
            }
        }).success(function (response) {
            if (response.indexOf('required') > -1 || response.indexOf('failed') > -1) {
                var html = '<div class="alert alert-danger" role="alert">' + response + '</div>';
                $('#message_area').html(html);
            } else if (response.indexOf('Invoice') > -1) {
                window.location.href = '<?php //echo Application::createActionUrl('business-invoice-manager');               ?>';
            } else if (response.indexOf('success') > -1) {
                if (closePage == true) {
                    window.location.href = '<?php //echo Application::createActionUrl('business-invoice-manager') . '?msg=You successfully update your invoice.';               ?>';
                } else {
                    window.location.href = '<?php //echo Application::createActionUrl('business-invoice-edit') . '?InvoiceId=' . $invoice['UniqueId'] . '&msg=You successfully update your invoice.';               ?>';
                    var html = '<div class="alert alert-success" role="alert"><a class="close" data-dismiss="alert">×</a>You successfully update your invoice.</div>';
                    $('#message_area').html(html);
                }
            }
        }).error(function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }).complete(function () {
        });
        return false;
    }
</script>
<script>
    $(function () {
        setTimeout(function () {
            $('#message_area').hide();
        }, 5000);
    });
</script>
@stop