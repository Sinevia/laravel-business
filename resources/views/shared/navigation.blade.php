<?php
$transactionCount = \Sinevia\Business\Models\Transaction::count();
$invoicesCount = \Sinevia\Business\Models\Invoice::where('Status', '<>', 'Deleted')->count();
?>
<div class="card card-default">
    <div class="card-body" style="padding: 2px;">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo \Sinevia\Business\Helpers\Links::adminHome(); ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo \Sinevia\Business\Helpers\Links::adminTransactionManager(); ?>">
                    Transactions
                    <span class="badge badge-default"><?php echo $transactionCount; ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo \Sinevia\Business\Helpers\Links::adminInvoiceManager(); ?>">
                    Invoices
                    <span class="badge badge-default"><?php echo $invoicesCount; ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>