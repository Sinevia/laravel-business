<?php

class PackageSineviaBusinessTablesCreate extends Illuminate\Database\Migrations\Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Sinevia\Business\Models\Transaction::tableCreate();
        Sinevia\Business\Models\Invoice::tableCreate();
        Sinevia\Business\Models\InvoiceItem::tableCreate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Sinevia\Business\Models\Transaction::tableDelete();
        Sinevia\Business\Models\Invoice::tableDelete();
        Sinevia\Business\Models\InvoiceItem::tableDelete();
    }

}
