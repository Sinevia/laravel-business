<?php

namespace Sinevia\Business\Models;

class Customer extends \App\Models\BaseModel {

    use \App\Models\Metas\HasMetas;

    protected $table = 'snv_customers_customer';
    protected $primaryKey = 'Id';
    public static $statusList = [
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Banned' => 'Banned',
        'Deleted' => 'Deleted',
    ];
    
    public function scopeActive($query){
        return $query->where('Status','Active');
    }
    
    public function getAge() {
        $birthTime = strtotime($this->Birthday);
        $tz = new \DateTimeZone('Europe/London');
        $age = (new \DateTime)->setTimestamp($birthTime)->diff(new \DateTime('now', $tz))->y;
        return $age;
    }

    public static function tableCreate() {
        $o = new static();

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            $result = \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                $table->engine = 'InnoDB';
                $table->string($o->primaryKey, 40)->primary();
                $table->enum('Status', array('Active', 'OnHold', 'Deleted'))->default('Active');
                $table->string('FirstName', 255);
                $table->string('LastName', 255);
                $table->string('Email', 255)->unique()->index();
                $table->string('Password', 255)->default('');
                $table->string('Country', 2)->default('');
                $table->string('Province', 100)->default('');
                $table->string('City', 100)->default('');
                $table->string('District', 100)->default('');
                $table->string('Address1', 255)->default('');
                $table->string('Address2', 255)->default('');
                $table->string('Postcode', 50)->default('');
                $table->string('PhoneLandline', 50)->default('');
                $table->string('PhoneMobile', 50)->default('');
                $table->string('Fax', 50)->default('');
                $table->string('PictureUrl', 255)->default('');
                $table->date('Birthday')->nullable();
                $table->datetime('LastLoginAt')->nullable();
                $table->datetime('CreatedAt')->nullable();
                $table->datetime('DeletedAt')->nullable();
                $table->datetime('UpdatedAt')->nullable();
            });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new static();
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
