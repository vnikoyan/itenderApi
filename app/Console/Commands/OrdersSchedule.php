<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order\Order;
use App\Http\Controllers\Api\Mail\MailController;
use App\Jobs\ProcessOrdersEmailNotification;
use Illuminate\Support\Facades\Log;

class OrdersSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrdersSchedule:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will check and update users aboute order end date ';

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
        $url = \Config::get('values')['frontend_url'];
        $nowDate = date("Y-m-d H").':00';
        $data = new \stdClass();
        $orders = Order::join('users','users.id','=', 'order.user_id')
                       ->where('order.type','ACTIVE')
                       ->get();
        $mailController = new MailController;
        $data->subject = "Ծանուցում փաթեթի ժամկետի ավարտի մասին";
        foreach($orders as $val){
            $val->email = $val->email;
        	$date = $val->end_date;
        	$month_date = date('Y-m-d H', strtotime($date. ' - 30 days')).':00';
        	if (strtotime($month_date) == strtotime($nowDate)){
        		$end_date = date("Y-m-d",strtotime($date));
        		$end_time = date("H:i",strtotime($date));
                $data->text  =  "<p>Հարգելի գործընկեր <br/> Տեղեկացնում ենք, որ iTender համակարգից օգտվելու ծառայությունների փաթեթի ժամկետն ավարտվում է 30 օր հետո՝ ".$end_date."թ․, ժամը՝ ".$end_time." ։</p>";
                $data->email = $val->email;
                Log::channel('mail')->info('30 oric prcnumaaa '.$data->email);
                ProcessOrdersEmailNotification::dispatch($data);

        	}

        	$days_date = date('Y-m-d H', strtotime($date. ' - 3 days')).':00';
           	if ( strtotime($days_date) == strtotime($nowDate)){
        		$end_date = date("Y-m-d",strtotime($date));
        		$end_time = date("H:i",strtotime($date));
                $data->email = $val->email;
        		$data->text = "<p>Հարգելի գործընկեր <br/> Տեղեկացնում ենք, որ iTender համակարգից օգտվելու ծառայությունների փաթեթի ժամկետն ավարտվում է 3 օր հետո՝ ".$end_date."թ․, ժամը՝ ".$end_time." ։ </p></br><a href =".$url.'/packages'.">Փաթեթի վերաակտիվացում</a></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
                Log::channel('mail')->info('3 oric prcnumaaa '.$data->email);
                ProcessOrdersEmailNotification::dispatch($data);

        	}

        }

        $orders = Order::select("users.*","order.*","order.id as orderId")
                       ->join('users','users.id','=', 'order.user_id')
                       ->where('order.type','ACTIVE')
                       ->where('order.package_id',"!=","1")
                       ->get();
        $nowDate = date("Y-m-d H:i:s");
        foreach($orders as $val){
            $data->email = $val->email;
            $date = $val->end_date;
            $data->subject = "Փաթեթի ժամկետն ավարտվել է !!!";
            if ( strtotime($date) < strtotime($nowDate)){
                $end_date = date("Y-m-d",strtotime($date));
                $end_time = date("H:i",strtotime($date));
                $data->text = "<p>Հարգելի գործընկեր <br/>  Տեղեկացնում ենք, որ iTender համակարգից օգտվելու ծառայությունների փաթեթի ժամկետն ավարտվել է</p></br></br><a href =".$url.">Փաթեթի վերաակտիվացում</a></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
                if( $val->payment_method = "trial period" && $val->amount_paid == 0 ){
                    $data->subject = "ԱՆՎՃԱՐ փորձաշրջանի ավարտ ";
                    $data->text = "<p>Հարգելի գործընկեր, iTender համակարգից 10 օր անվճար օգտվելու ժամկետն ավարտվել է։ Խնդրում ենք թողնել համակարգի վերաբերյալ Ձեր կարծիքը:Համակարգի ծառայություններին, փաթեթների հնարավորություններին կարող եք ծանոթանալ <a href = '".$url.'/?to=packages'."'>այստեղ։</a></p></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";                                             
                }
                Log::channel('mail')->info('porcasharjany prcnavvv '.$data->email);
                ProcessOrdersEmailNotification::dispatch($data);
                Order::where('id', $val->orderId)
                      ->update(['type' => "SUSPENDED"]);
            }
        }
    }
}
