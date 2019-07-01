<?php

namespace Sinevia\Business\Models;

class Transaction extends BaseModel {
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'snv_accounting_transaction';
    protected $primaryKey = 'Id';

    function isCredit(){
        if(strtolower($this->IsCredit)=='yes'){
            return true;
        }
        return false;
    }

    function isDebit(){
        if(strtolower($this->IsDebit)=='yes'){
            return true;
        }
        return false;
    }

    public static function tableCreate() {
        $o = new self;

        if (\Schema::connection($o->connection)->hasTable($o->table) == true) {
            return true;
        }

        return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                    $table->engine = 'InnoDB';
                    $table->string($o->primaryKey, 40)->primary();
                    $table->enum('IsCredit', ['No','Yes']);
                    $table->enum('IsDebit', ['No','Yes']);
                    $table->string('Title', 255);
                    $table->text('Description')->nullable()->default(NULL);
                    $table->decimal('Amount',10,2)->nullable()->default(NULL);
                    $table->date('Date')->nullable()->default(NULL);
                    $table->text('Memo')->nullable()->default(NULL);
                    $table->datetime('CreatedAt')->nullable()->default(NULL);
                    $table->datetime('UpdatedAt')->nullable()->default(NULL);
                    $table->datetime('DeletedAt')->nullable()->default(NULL);
                });
    }

    public static function tableDelete() {
        $o = new self;

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return true;
        }

        return \Schema::connection($o->connection)->drop($o->table);
    }

}
