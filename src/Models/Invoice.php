<?php

namespace Sinevia\Business\Models;

class Invoice extends BaseModel {

    protected $table = 'snv_business_invoice';
    protected $primaryKey = 'Id';

    const STATUS_DRAFT = 'Draft';
    const STATUS_UNPAID = 'Unpaid';
    const STATUS_PAID = 'Paid';
    const STATUS_DELETED = 'Deleted';

    public function items() {
        return $this->hasMany('Sinevia\Business\Models\InvoiceItem', 'InvoiceId');
    }

    public function getReference() {
        $id = $this->Id;
        $date = substr($id, 0, 4) . '-' . substr($id, 4, 2) . '-' . substr($id, 6, 2) . ' ' . substr($id, 8, 2) . ':' . substr($id, 10, 2) . ':' . substr($id, 12, 2);
        $ref = strtoupper(base_convert(strtotime($date),10,32));
        return $ref;
    }

    public static function tableCreate() {
        $o = new static;

        if (\Schema::connection($o->connection)->hasTable($o->table) == true) {
            return true;
        }

        return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                    $table->engine = 'InnoDB';
                    $table->string($o->primaryKey, 40)->primary();
                    $table->string('Status', 40)->index()->nullable()->default(NULL);
                    $table->string('BusinessId', 40)->index()->nullable()->default(NULL);
                    $table->string('CustomerId', 40)->index()->nullable()->default(NULL);
                    $table->string('Currency', 40)->default('GBP')->index();
                    $table->decimal('Discount', 10, 2)->default('0.00');
                    $table->text('DiscountDescription')->nullable()->default(NULL);
                    $table->decimal('Subtotal', 10, 2)->default('0.00');
                    $table->decimal('Tax', 10, 2)->default('0.00');
                    $table->decimal('Total', 10, 2)->default('0.00');
                    $table->datetime('DueAt')->nullable()->default(NULL);
                    $table->datetime('IssuedAt')->nullable()->default(NULL);
                    $table->datetime('PaidAt')->nullable()->default(NULL);
                    $table->string('Description')->nullable()->default(NULL);
                    $table->string('Reference', 40)->nullable()->default(NULL);
                    $table->string('TransactionId', 40)->index()->nullable()->default(NULL);
                    $table->text('Memo')->nullable()->default(NULL);
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
