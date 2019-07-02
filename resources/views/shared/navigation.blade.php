<?php
$transactionCount = \Sinevia\Accounting\Models\Transaction::count();
$invoicesCount = \Sinevia\Accounting\Models\Invoice::where('Status', '<>', 'Deleted')->count();
?>
<div class="panel panel-default">
    <div class="panel-body" style="padding: 2px;">
        <ul class="nav nav-pills">
            <li>
                <a href="<?php echo \Sinevia\Accounting\Helpers\Links::adminHome(); ?>">Dashboard</a>
            </li>
            <li>
                <a href="<?php echo \Sinevia\Accounting\Helpers\Links::adminTransactionManager(); ?>">
                    Transactions
                    <span class="badge"><?php echo $transactionCount; ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo \Sinevia\Accounting\Helpers\Links::adminInvoiceManager(); ?>">
                    Invoices
                    <span class="badge"><?php echo $invoicesCount; ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>