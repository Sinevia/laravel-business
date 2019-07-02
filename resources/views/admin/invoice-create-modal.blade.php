<?php
$customerList = App\Models\Customers\Customer::active()->orderBy('FirstName', 'ASC')->get();
?>
<!-- START: Invoice Create Dialog -->
<div class="modal fade" id="ModalInvoiceCreate" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="FormInvoiceCreate">
                    <div class="alert alert-success" style="display:none;"></div>
                    <div class="alert alert-danger" style="display:none;"></div>

                    <div class="form-group">
                        <label>
                            Customer <sup style="color:red;">*</sup>
                        </label>
                        <select name="CustomerId" class="form-control">
                            <option value=""></option>
                            <?php foreach ($customerList as $c) { ?>
                                <option value="<?php echo $c->Id ?>" ><?php echo $c->FirstName ?> <?php echo $c->LastName ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-info pull-left float-left" data-dismiss="modal">
                    <i class="fa fa-chevron-left"></i>
                    Cancel
                </a>
                <button class="btn btn-success" onclick="formInvoiceCreateSubmit();">
                    <i class="fa fa-check"></i>
                    Continue
                    <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showInvoiceCreateDialog(options) {
        $('#ModalInvoiceCreate').modal('show');
    }
    function formInvoiceCreateSubmit(options) {
        var invoiceCreateUrl = '<?php echo \Sinevia\Accounting\Helpers\Links::adminInvoiceCreate(); ?>';
        var data = $('.FormInvoiceCreate :input').serialize();
        $('#ModalInvoiceCreate button .fa-spinner').show();

        $('#ModalInvoiceCreate .alert-success').fadeOut().html('');
        $('#ModalInvoiceCreate .alert-danger').fadeOut().html('');

        $.post(invoiceCreateUrl, data).then(function (responseString) {
            var response = JSON.parse(responseString);
            console.log(response)

            if (response.status === "success") {
                var invoiceId = response.data.InvoiceId;
                var invoiceUrl = response.data.InvoiceUrl;
                var messages = response.message;
                $('.FormInvoiceCreate .alert-success').fadeIn().html(messages);
                window.location.href = invoiceUrl;
                return;
            }

            if (response.status === "error") {
                var messages = response.message;
                messages = $.isArray(messages) ? messages.join('<br />') : messages;
                $('.FormInvoiceCreate .alert-danger').fadeIn().html(messages);
                return;
            }
        }).fail(function (response) {
            $('.FormInvoiceCreate .alert-danger').fadeIn().html('There was server error. Please try again later');
        }).always(function () {
            $('#ModalInvoiceCreate button .fa-spinner').hide();
        });
    }
</script>
<!-- END: Invoice Create Dialog -->