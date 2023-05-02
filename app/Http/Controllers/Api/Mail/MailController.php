<?php

namespace  App\Http\Controllers\Api\Mail ;
use Mail;
use Auth;
use App\Models\User\User;
use App\Models\Settings\VtbReport;
use App\Http\Controllers\Api\Mail\ForgotPasswordLink;
use App\Http\Controllers\Api\Mail\itenderMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\AbstractController;
use Illuminate\Support\Facades\Log;

class MailController extends AbstractController
{
    public function ForgotPasswordLink(Request $request)
    {   

        $isValid = Validator::make($request->toArray(), [
            'username' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        $user = User::where('username',$request->input('username'))->first();
        if( !empty($user) ){
            $token = (string) str_random(60);
            $url = \Config::get('values')['frontend_url'].'/forgot/password/'.$token;
            $user->forgot_token = $token;
            $user->forgot_token_expires = now()->addHours(24);
            $user->save();
            $html = "<p>Հարգելի՛ օգտատեր,</p></br><p>Գաղտնաբառի վերականգնման համար խնդրում ենք անցնել հետևյալ հղմամբ․</p></br><a href =".$url.">անցնել հղմամբ</a></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
            $subject = "Գաղտնաբառի վերականգնում !!!";
            $this->new_mail($user->email,$subject,$html);
            return response()->json(['error' => false, 'message' => 'mail was sent']);
        }else{
            return response()->json(['error' => true, 'message' => 'mail not found']);
        }
    }

    public function ForgotPasswordPage( $token ){
        $url = \Config::get('values')['frontend_url'];

        return  redirect( $url."/reset/password/".$token ) ;
    }


    public function new_mail($email,$subject,$html, $promotion = 'iTender'){
        ini_set('max_execution_time', 600);
        $array = array(
            "username"=>'info@itender.am',
            "api_key"=>'3917df898d73fcc92c642724aba12a35',
            "promotion_name"=> 'Ծանուցում',
            // "recipient"=>'<'.trim($email).'>',
            // "recipient"=>'<'.trim('ping@tools.mxtoolbox.com').'>',
            "recipient"=>'<'.trim('hikespammail@gmail.com').'>',
            "subject"=> $subject,
            "from"=>'iTender <info@itender.am>',
            "raw_html"=>$this->mailViewNew($html)
        );
        $ch = curl_init('https://api.madmimi.com/mailer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        $result = curl_exec($ch);
        $errors = curl_error($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($errors){
            Log::channel('mail')->info('Էլ հասցեն: '.$email.' Վերնագիր: '.$subject.' Խնդիր / error -'.$errors);
            Log::channel('mail')->info('RETRY');
            $result = curl_exec($ch);
            $errors = curl_error($ch);
            $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($errors){
                Log::channel('mail')->info('Mail: '.$email.' Subject: '.$subject.' Խնդիր / error -'.$errors);
            } elseif($response){
                Log::channel('mail')->info('Mail: '.$email.' Subject: '.$subject.' Պատասխան / response -'.$response);
            }
        } elseif($response){
            Log::channel('mail')->info('Mail: '.$email.' Subject: '.$subject.' Պատասխան / response -'.$response);
        }
        curl_close($ch);
    }

    function itender_email($to ,$subj, $html){

        $url = \Config::get('values')['frontend_url'];
        $data = [
            'subj'  => $subj,
            'html'  => $html,
            'url'   => $url,
        ];

        try {
            Mail::to($to)->send(new itenderMail($data));
        } catch (\Throwable $th) {
            Log::info($th);
        }
          
    }

    public function accountActivate(Request $request){
        $user = User::where('verify_token',$request->input('token'))->where("email_verified_at","=",null)->first();
        User::where('verify_token', $request->input('token'))
              ->update(['email_verified_at' => date('Y-m-d H:i:s'),'is_confirmed' => '1']);
        if(is_null($user)){
            return $this->respondWithStatus(true, [
            'message' => 'Էլեկտրոնային հասցեն արդեն իսկ հաստատված է',
            ], 201);
        }
        $subj = "Անձնական էջի ակտիվացում iTender համակարգում";
        $html = "<p>Հարգելի գործընկեր, Սիրով տեղեկացնում ենք, որ Ձեր հաշիվը iTender համակարգում հաջողությամբ ակտիվացված է:</p></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
        $this->new_mail($user->email,$subj,$html);
        $subj = "iTender համակարգից օգտվելու ուղեցույց";
        $html = "<p>iTender համակարգի հնարավորություներին մանրամասն ծանոթանալու համար, խնդրում ենք անցնել հետևյալ հղումով</p></br><a href ='https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'>անցնել հղմամբ</a></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
        $this->new_mail($user->email,$subj,$html);
        return $this->respondWithStatus(true, [
            'message' => 'Էլեկտրոնային հասցեն հաջողությամբ հաստատված է',
        ], 201);
    }

    public function sendGuarantee(Request $request){
        $isValid = Validator::make($request->toArray(), [
            'name' => ['required'],
            'director_name' => ['required'],
            'phone' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $html = "<p>ԱՆՎԱՆՈՒՄ: ".$request->input("name")."</p><br><p>ՏՆՕՐԵՆԻ ԱՆՈՒՆ ԱԶԳԱՆՈՒՆ: ".$request->input("director_name")."</p></br><p>ՀԵՌԱԽՈՍԱՀԱՄԱՐԸ: ".$request->input("phone")."</p>";
        
        $user = auth('api')->user();
        $VtbReport = new VtbReport;
        $VtbReport->user_id = $user->id;
        $VtbReport->action = "guarantee request";
        $VtbReport->save();

        $url = \Config::get('values')['frontend_url'];
        $data = [
            'subj'  => 'Նամակ ITender համակարգից',
            'html'  => $html,
            'url'   => $url,
        ];

        Mail::to('onlineappcorp@vtb.am')->send(new itenderMail($data));
         
    }
    
    public function mailView($html){
        $url = \Config::get('values')['frontend_url'];
        $image = '<img src="'.asset('images/logo.png').'" title="iTender" width="150" height="38" style="display:block" ></a>';
        return '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody><tr>
                    <td style="padding:10px 0 30px 0">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="800" bgcolor="#fff" style="border: 3px solid #0078cc;">
                            <tbody>
                            <tr bgcolor="#1E7BB7">
                                <td align="left" width="10" height="92"></td>
                                <td width="680" height="92" align="left">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td align="center">
                                                <a href='."$url".' target="_blank">
                                                '.$image.'
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                                <td align="left" width="10" height="92"></td>
                            </tr>
                            <tr>
                                <td align="left" width="10" height="92"></td>
                                
                                <td align="left" width="10" height="92"></td>
                            </tr>
                            <tr>
                                <td align="left" width="10" height="92"></td>
                                <td width="680" height="92" align="left">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="color:#333333;font-size:14px;line-height:18px;font-family:Segoe UI,Arian AMU,sans-serif;padding:0 30px 30px 30px">
                                                '.$html.'
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="1" bgcolor="#0078cc"></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#333333;font-size:12px;line-height:16px;font-family:Segoe UI,Arian AMU,sans-serif;padding:30px">
                                                Խնդրում ենք չպատասխանել այս նամակին:<br>Հարցերը կարող եք ուղարկել
                                                <a style="color:#0078cc" href="mailto:itender@protender.am" target="_blank">info@itender.am</a> էլ. հասցեին
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                                <td align="left" width="10" height="92"></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                </tbody>
                </table>';
    }

    public function mailViewNew($html){
        $url = \Config::get('values')['frontend_url'];
        $image = '<a href='."$url".' target="_blank"><img src="https://api.itender.am/images/logo.png" style="height: 30px !important;
        margin-top: 20px;
        margin-bottom: 20px;"></a>';
        return '
        <div>
            <div style="display: flex;">
                <div style="width: 100%; height: 70px; background-color: #006BE6; display: flex; align-items: center; padding-left: 5%;">
                    '.$image.'
                </div>
                <div style="width: 0; height: 0; border-top: 35px solid transparent; border-bottom: 35px solid transparent; border-left: 35px solid #006BE6;"></div> 
                <div style="display: flex; margin-left: 25px;">
                    <div style="width: 0; height: 0; border-top: 35px solid #006BE6; border-bottom: 35px solid #006BE6; border-left: 35px solid transparent; border-right-color: transparent;"></div> 
                    <div style="width: 30px; height: 70px; background-color: #006BE6;"></div>
                    <div style="width: 0; height: 0; border-top: 35px solid transparent; border-bottom: 35px solid transparent; border-left: 35px solid #006BE6;"></div> 
                </div>
            </div>
        </div>
        <div style="padding-left: 54px;">
            <h1 style="color: #0E0F12;font-weight: 700;font-size: 20px;line-height: 28px; margin: 60px 0 25px;">Ծանուցում</h1>
            <div style="margin-bottom: 45px;">
                '.$html.'
            </div>
        </div>
        <div style="border-top: 1px solid #EDEFF2; margin: 0 50px;"></div>
        <div style="padding: 25px 0 25px 50px; border-bottom: 4px solid #006BE6;">
            <div style="color: #595E6B; font-size: 14px; line-height: 20px;">
                <p style="margin: 0;">Խնդրում ենք չպատասխանել այս նամակին:</p>
                <p style="margin: 0;">Հարցերը կարող եք ուղարկել <a style="color: #0065D9; text-decoration: none;" href="mailto:info@itender.am">info@itender.am</a> էլ. հասցեին</p>
            </div>
        </div>';
    }

    public function mailNotifications(Request $request){
        $user_id = auth('api')->user()->id;
        $user = User::where('id',$user_id)->update(['email_notifications'=> $request->email_notification,'telegram_notifications'=> $request->telegram_notification]);
        return $user;
    }
}