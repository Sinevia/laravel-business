<!-- START: Invoice Delete Modal Dialog -->
<div class="modal fade" id="ModalInvoiceDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Confirm Invoice Delete</h3>
            </div>
            <div class="modal-body">
                <div>
                    Are you sure you want to delete this invoice?
                </div>
                <div>
                    Note! This action cannot be undone.
                </div>

                <form name="FormInvoiceDelete" method="post" action="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceDelete(); ?>">
                    <input type="hidden" name="InvoiceId" value="">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info pull-left" data-dismiss="modal">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-danger" data-dismiss="modal" onclick="FormInvoiceDelete.submit();">
                    <span class="glyphicon glyphicon-remove-sign"></span>
                    Delete Invoice
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmInvoiceDelete(page_id) {
        $('#ModalInvoiceDelete input[name=InvoiceId]').val(page_id);
        $('#ModalInvoiceDelete').modal('show');
    }
</script>
<!-- END: Invoice Delete Modal Dialog -->

<!-- START: Invoice Move to Trash Modal Dialog -->
<div class="modal fade" id="ModalInvoiceMoveToTrash">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Confirm Invoice Move to Trash</h3>
            </div>
            <div class="modal-body">
                <div>
                    Are you sure you want to move this invoice to trash?
                </div>

                <form name="FormInvoiceMoveToTrash" method="post" action="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceMoveToTrash(); ?>">
                    <input type="hidden" name="InvoiceId" value="">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info pull-left" data-dismiss="modal">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-danger" data-dismiss="modal" onclick="FormInvoiceMoveToTrash.submit();">
                    <span class="glyphicon glyphicon-trash"></span>
                    Move to Trash
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmInvoiceMoveToTrash(pageId) {
        $('#ModalInvoiceMoveToTrash input[name=InvoiceId]').val(pageId);
        $('#ModalInvoiceMoveToTrash').modal('show');
    }
</script>
<!-- END: Invoice Move to Trash Modal Dialog -->