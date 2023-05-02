<?php
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewMenuToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $permissions = [
                ["guard_name"=>"web","name"=>"bank_secure_stats","icon"=>"settings","code"=>"Երաշխիքի հաշվետվություն"],
            ];
    
            foreach ($permissions as $key => $permission) {
                 Permission::create(['name' => $permission["name"],'code' => $permission["code"],'icon' => $permission["icon"] ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
}
