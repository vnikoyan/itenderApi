<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract\ContractOrders;
class ContractOrdersSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ContractsOrders:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check and update contract orders status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $nowDate = date("Y-m-d H:i:s");
        $contract_orders = ContractOrders::where('status','sended')->get();
        foreach($contract_orders as $val){
            if(strtotime($nowDate) > strtotime($val->dispatch_date)){
                ContractOrders::where('id', $val->id)
                      ->update(['status' => 'canceled']);
            }
        }

    }
}
