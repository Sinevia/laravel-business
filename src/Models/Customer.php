<?php

namespace Sinevia\Business\Models;

class Customer extends BaseModel {

    protected $table = 'snv_business_customer';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    public $incrementing = false;
    public $useUniqueId = true;

    public function business() {
        return $this->belongsTo('App\Models\Customers\Business', 'BusinessId', 'Id');
    }

    public static function tableCreate() {
        $o = new self();

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                        $table->engine = 'InnoDB';
                        $table->string($o->primaryKey, 40)->primary();
                        $table->string('Status', 40)->default('')->index();
                        $table->string('BusinessId', 40)->index();
                        $table->string('Type', 40)->default('Company'); // Company / Sole Trader
                        $table->string('CustomerName', 255)->index();
                        $table->string('BusinessName', 255)->index();
                        $table->string('PersonFirstName', 255)->index();
                        $table->string('PersonLastName', 255)->index();
                        $table->string('Country', 255)->default('');
                        $table->string('Province', 255)->default('');
                        $table->string('City', 255)->default('');
                        $table->string('Address1', 255)->default('');
                        $table->string('Address2', 255)->default('');
                        $table->string('PostCode', 255)->default('');
                        $table->string('EmailAddressInvoice', 255)->default('');
                        $table->string('EmailAddressQuote', 255)->default('');
                        $table->string('Phone', 255)->default('');
                        $table->datetime('CreatedAt')->nullable()->default(NULL);
                        $table->datetime('UpdatedAt')->nullable()->default(NULL);
                        $table->datetime('DeletedAt')->nullable()->default(NULL);
                        $table->text('Memo');
                    });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new self();
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
