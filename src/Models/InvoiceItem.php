<?php

namespace Sinevia\Business\Models;

class InvoiceItem extends BaseModel {

    protected $table = 'snv_business_invoiceitem';
    protected $primaryKey = 'Id';

    public static function tableCreate() {
        $o = new static;

        if (\Schema::connection($o->connection)->hasTable($o->table) == true) {
            return true;
        }

        return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                    $table->engine = 'InnoDB';
                    $table->string($o->primaryKey, 40)->primary();
                    $table->string('Status', 40)->default('')->index();
                    $table->string('InvoiceId', 40)->index();
                    $table->text('Details');
                    $table->float('Units')->default('0.00');
                    $table->decimal('PricePerUnit', 10, 2)->default('0.00');
                    $table->decimal('Total', 10, 2)->default('0.00');
                    $table->text('Memo')->nullable()->default(NULL);;
                    $table->datetime('PaidAt')->nullable()->default(NULL);
                    $table->datetime('CreatedAt')->nullable()->default(NULL);
                    $table->datetime('UpdatedAt')->nullable()->default(NULL);
                    $table->datetime('DeletedAt')->nullable()->default(NULL);
                });
    }

    public static function tableDelete() {
        $o = new static;

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return true;
        }

        return \Schema::connection($o->connection)->drop($o->table);
    }

}
