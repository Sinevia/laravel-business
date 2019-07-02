<?php

namespace Sinevia\Business\Models;

class Business extends BaseModel {

    protected $table = 'snv_business_business';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    public $incrementing = false;

    public function customers() {
        return $this->hasMany('App\Models\Customers\Customer', 'BusinessId');
    }

    public static function tableCreate() {
        $o = new self();

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                        $table->engine = 'InnoDB';
                        $table->string($o->primaryKey, 40)->primary();
                        $table->string('Status', 40)->default('')->index();
                        $table->string('Type', 40)->default('Company'); // Company / Sole Trader
                        $table->string('BusinessName', 255)->index();
                        $table->string('Country', 255)->default('');
                        $table->string('Province', 255)->default('');
                        $table->string('City', 255)->default('');
                        $table->string('Address1', 255)->default('');
                        $table->string('Address2', 255)->default('');
                        $table->string('PostCode', 255)->default('');
                        $table->string('EmailAddressInvoice', 255)->default('');
                        $table->string('Phone', 255)->default('');
                        $table->string('BankSortCode', 255)->default('');
                        $table->string('AccountNumber', 255)->default('');
                        $table->datetime('Created')->default('0000-00-00 00:00:00');
                        $table->datetime('Updated')->default('0000-00-00 00:00:00');
                    });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new self();
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
