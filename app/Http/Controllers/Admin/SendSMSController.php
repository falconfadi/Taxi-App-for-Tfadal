<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SendSMS;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;



class SendSMSController extends Controller
{
    //use   CURL;
    public function index()
    {
        $title = __('menus.Send_sms');
        $smses = SendSMS::all();
        $D = new User();
        $drivers = $D->getAllDrivers();
        $users = $D->getAllUsers();//User::where('is_driver',0)->whereNotNull('fcm_token')->get();


        return view('admin.sendsms.index',compact('smses','users','title','drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDriverAlertRequest  $request
     * @return \Illuminate\Http\Response
     */
    //send sms drivers
    public function store(Request $request)
    {
        $U = new User();
        $dIds = array();
        if($request->input('all')){
            $driversIds = $U->getDriversIds();
            $dIds =  $driversIds;
        }else{
            $dIds =$request->drivers;
        }
        foreach ($dIds as $driver){
            $x = new SendSMS();
            $user = User::find($driver);
            $x->msg = $request->input('msg');
            $x->user_id = $driver;
            //send msg
            if( $this->send($x->msg ,$user->phone))
            {
                $x->save();
            }
        }
        Session::flash('alert-success','تم إرسال رسالة جديدة');
        return redirect('admin/send-sms');
    }
    //send sms users
    public function store_users(Request $request)
    {
        //var_dump($request->input('all'));exit();
        $U = new User();
        $uIds = array();
        if($request->input('all')){
            $usersIds = $U->getUsersIds();
            $uIds =  $usersIds;
        }else{
            $uIds =$request->users;
        }
        foreach ($uIds as $userId){
            $x = new SendSMS();
            $x->msg = $request->input('msg');
            $x->user_id = $userId;
            $user = User::find($userId);
            if( $this->send($x->msg ,$user->phone)) {
                $x->save();
            }
        }

        Session::flash('alert-success','تم إرسال رسالة جديدة');
        return redirect('admin/send-sms');

    }

    public function sendSMS($msg ,$number){
        $number = ltrim($number, '0');
        $number = '963'.$number;
       // $url = 'https://test.friends-sy.tel/api/sendSMSApi?msg='.$msg.'&mobile='.$number;
        $url ="https://services.mtnsyr.com:7443/General/MTNSERVICES/ConcatenatedSender.aspx?User=frie353&Pass=stel131513&From=Friendstel&Gsm=".$number ."&Msg=".$msg."&Lang=0";
        $contents = file_get_contents($url);
        //If $contents is not a boolean FALSE value.
        if($contents !== false){
            //Print out the contents.
            return $contents;
        }else{
            return false;
        }
    }

    public function sendSmsMTN($msg, $mobile){

        $ch = \curl_init();
        $url = "https://services.mtnsyr.com:7443/general/MTNSERVICES/ConcatenatedSender.aspx?User=tuiui254&Pass=thyhh121514&From=Tfadal&Gsm=".$mobile."&Msg=".$msg."&Lang=0";

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPGET => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION =>1
        ));
        $html = curl_exec($ch);
        if(empty($html)){
            try{
                return false;
            }finally{
                curl_close($ch);
            }
        }
        if ($ch != null)
            curl_close($ch);
        return ["html"=>$html,'ch'=>$ch];
    }
//    public function sendSmsSyriatel($msg, $number){
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://bms.syriatel.sy/API/SendSMS.aspx?job_name=Tfadal&user_name=Tfadal&password=P@123456&msg=".$msg."&sender=Tfadal&to=".$number);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        if (curl_exec($ch) === false) {
//            //fadi
//           // echo 'Curl error: ' . curl_error($ch);
//        } else {
//           // echo 'Operation completed without any errors';
//
//        }
//        curl_close($ch);
//    }

    public function sendSmsSyriatel($msg, $number){

        $ch = curl_init();

        $url = "https://bms.syriatel.sy/API/SendTemplateSMS.aspx?user_name=Tfadal&password=P@123456&template_code=Tfadal_T1&param_list=test&sender=Tfadal&to=".$number;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $html = curl_exec($ch);
        if(empty($html)){
            try{
                return false;
            }finally{
                curl_close($ch);
            }
        }
        if ($ch != null)
            curl_close($ch);
       // return ["html"=>$html,'ch'=>$ch];
    }

//    public function send($msg,$mobile):void
//    {
//        $mobile = ltrim($mobile, '0');
//        $mobile = '963'.$mobile;
//
//
//        if (!preg_match('/^9639\d{8}$/', $mobile)) {
//           // return false;
//        }
//
//        if (empty($msg)){}
//            //return "Empty Message";
//
//        if (preg_match('/[^A-Za-z0-9]/', $msg)) {
//
//            $msg = self::convert2english($msg);
//        }
//
//        //$msgurl = self::ConvertoToUnicode(trim($msg));
//        //$this->sendSmsMTN($msgurl, $mobile);
//
//        //$this->smsglobal($msg,$mobile);
//        $this->sendSmsSyriatel($msg, $mobile);
//    }

    public function send($msg,$mobile)
    {
        $mobile = ltrim($mobile, '0');
        $mobile = '963'.$mobile;

        if (!preg_match('/^9639\d{8}$/', $mobile)) {
             return false;
        }

        if (empty($msg))
        return "Empty Message";

        if (preg_match('/[^A-Za-z0-9]/', $msg)) {

            $msg = self::convert2english($msg);
        }

       // $msgurl = self::ConvertoToUnicode(trim($msg));
        $this->sendSmsMTN($msg, $mobile);
        //return $f;

        //$this->smsglobal($msg,$mobile);
        //$this->sendSmsSyriatel($msg, $mobile);

    }

    public function smsglobal($msg, $mobile){
        $ch = \curl_init();
        $url = "https://api.smsglobal.com/http-api.php?action=sendsms&user=fdr48a35&password=RLWlmiOL&from=tfadal&to=".$mobile."=&text=".$msg;

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPGET => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 0
        ));
        $html = curl_exec($ch);
        if(empty($html)){
            try{
                return false;
            }finally{
                curl_close($ch);
            }
        }
        if ($ch != null)
            curl_close($ch);
        return ["html"=>$html,'ch'=>$ch];
    }
    public static function convert2english($string)
    {
        $newNumbers = range(0, 9);
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        return str_replace($arabic, $newNumbers, $string);
    }

    private static function ConvertoToUnicode($text)
    {
        $arrytext = self::mbStringToArray($text);

        $characters = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "ء", "آ", "أ", "ؤ", "إ", "ئ", "ا", "ب", "ة", "ت", "ث", "ج", "ح", "خ", "د", "ذ", "ر", "ز", "س", "ش", "ص", "ض", "ط", "ظ", "ع", "غ", "ـ", "ف", "ق", "ك", "ل", "م", "ن", "ه", "و", "ى", "ي", " ", "\r", "\n", "!", "\"", "#", "$", "%", "&", "'", "(", ")", "*", "+", ",", "-", ".", "/", "@", "~", "^", "_", "|", "`", ":", ";", "<", "=", ">", "?", "؛", "×", "÷", "‘", "ٌ", "ُ", "ً", "َ", "ّ", "؟", "’", "{", "}", "ْ", "[", "]", "ِ", "ٍ", "،", "\\");
        $unicode_trans = array("0030", "0031", "0032", "0033", "0034", "0035", "0036", "0037", "0038", "0039", "0041", "0042", "0043", "0044", "0045", "0046", "0047", "0048", "0049", "004A", "004B", "004C", "004D", "004E", "004F", "0050", "0051", "0052", "0053", "0054", "0055", "0056", "0057", "0058", "0059", "005A", "0061", "0062", "0063", "0064", "0065", "0066", "0067", "0068", "0069", "006A", "006B", "006C", "006D", "006E", "006F", "0070", "0071", "0072", "0073", "0074", "0075", "0076", "0077", "0078", "0079", "007A", "0621", "0622", "0623", "0624", "0625", "0626", "0627", "0628", "0629", "062A", "062B", "062C", "062D", "062E", "062F", "0630", "0631", "0632", "0633", "0634", "0635", "0636", "0637", "0638", "0639", "063A", "0640", "0641", "0642", "0643", "0644", "0645", "0646", "0647", "0648", "0649", "064A", "0020", "000D", "000A", "0021", "0022", "0023", "0024", "0025", "0026", "0027", "0028", "0029", "002A", "002B", "002C", "002D", "002E", "002F", "0040", "007E", "005E", "005F", "007C", "0060", "003A", "003B", "003C", "003D", "003E", "003F", "061B", "00D7", "00F7", "0091", "064C", "064F", "064B", "064E", "0651", "061F", "0092", "007B", "007D", "0652", "005B", "005D", "0650", "064D", "060C", "005C");

        $text = '';
        for ($i = 0; $i < count($arrytext); $i++) {
            if ($arrytext[$i] == '0') {
                $text2[$i] = '0030';
            } else if ($arrytext[$i] == '1') {
                $text2[$i] = '0031';
            } else if ($arrytext[$i] == '2') {
                $text2[$i] = '0032';
            } else {
                $text2[$i] = str_replace($characters, $unicode_trans, $arrytext[$i]);
            }
            $text = $text . $text2[$i];
        }
        return $text;
    }


    private static function mbStringToArray($string)
    {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    public function destroy($id)
    {
        $res = SendSMS::find($id)->delete();

        return back()->with('success','Faq deleted successfully');
    }
    public function test(){
       // echo "fdgf";
        $x = 'Verification%20Code:%20'."5555";
        $this->send($x, '0988728260');
       // $this->sendSmsSyriatel('','0988728260');

    }
}
