<?php
namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


class Language extends Model{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const FALLBACK_LOCALE = 1;

    protected $table = 'language';
    protected static $fallback_locale;

    /**
     * set $fallback_locale property
     */
    public function __construct()
    {
        parent::__construct();
        self::$fallback_locale = Config::get('app.fallback_locale');
    }
    /*
     * @param $status: 0 or 1
     * @return object
     */
    public static function getLanguages($status = null){
        if($status === null){
            return self::get();
        }
        return self::where('status', $status)->get();
    }

    /*
     * @return array
     */
    public static function getInactiveLang(){
        return Language::where('status', Language::STATUS_INACTIVE)->get();
    }

    /*
     * @return array
     */
    public static function getLangCodeCountry($status = null){
        if($status === null){
            $status = self::STATUS_INACTIVE;
        }
        return Language::select('lang_country as language')
                    ->where('status', Language::STATUS_INACTIVE)
                    ->get();
    }

    /*
     * @param code: language direction folder name
     * @return array
     */
    public static function getTranslationByLang($code){

        if($code == self::$fallback_locale){
            return self::getDefaultLanguage();
        }
        $messages   = include(realpath(base_path('resources/lang') . "/$code/messages.php"));
        $validation = include(realpath(base_path('resources/lang') . "/$code/validation.php"));

        return [
            'current' => [
                'messages'  => $messages,
                'validation' => $validation,
                ],
            'default' => self::getDefaultLanguage()
        ];
    }


    public static function getDefaultLanguage(){
        $code       = self::$fallback_locale;
        
        $messages  = include(realpath(base_path('resources/lang') . "/$code/messages.php"));
        $validation = include(realpath(base_path('resources/lang') . "/$code/validation.php"));

        return [
            'messages'  => $messages,
            'validation' => $validation,
        ];
    }

    public static function saveTranslation($request){
        $directory = scandir(realpath(base_path('resources/lang') . "/$request->code/"));
        $file = $request->chapter;
        if( !in_array($file . '.php', $directory) ){
            return false;
        }
        if($request->id){
            self::updateLanguage(self::find($request->id));
        }
        $langDir = realpath(base_path('resources/lang') . "/$request->code/$file.php");
        $lang = include($langDir);
        if($file = 'validation'){
            foreach($request->language as $rule => $msg){
                if($rule == 'custom'){
                    foreach($msg as $attr => $err){
                        foreach($err as $name => $value){
                            $lang[$rule][$attr][$name] = $value;
                        }
                    }
                }else{
                    $lang[$rule] = $msg;
                }
            }
        }else{
            foreach($request->language as $rule => $msg){
                $lang[$rule] = $msg;
            }
        }
        $translation = "<?php \n return \n ". var_export($lang, 1) ."; ?>";
        return  file_put_contents($langDir, $translation);
    }

    public static function updateLanguage($language){
        $language->updated_at = new \DateTime();
        $language->save();
    }

    public static function _save($request){
        if($request->id){
            $language = self::find($request->id);
        }else{
            $language = self::where('lang_country', $request->language)->first();
            $language->status = self::STATUS_ACTIVE;
        }
        if($language){
            if ('on' == strtolower($request->fallback_locale)) {
                $language->fallback_locale = 1;
                self::where('fallback_locale', self::FALLBACK_LOCALE)
                    ->update(['fallback_locale' => 0]);
            }
            return $language->save();
        }
        return null;
    }

    public static function _delete($id){
        if(Language::where('status', self::STATUS_ACTIVE)->get()->count() == 1){
            return false;
        }
        $language = Language::find($id);
        if($language->fallback_locale == self::FALLBACK_LOCALE){
            $language->fallback_locale = 0;
            $fallback = self::where('status', self::FALLBACK_LOCALE)
                ->where('id', '<>', $id)->first();
            self::setFallbackLocale($fallback);
        }
        $language->status = Language::STATUS_INACTIVE;
        return $language->save();
    }

    /*
     * @return integer
     */
    public static function setFallbackLocale($language){
        $language->fallback_locale = self::FALLBACK_LOCALE;
        return $language->save();
    }

    public static function insertKey($data,$id,$file = "messages"){
        $directory = scandir(realpath(base_path('resources/lang/') . "en"));
        if( !in_array($file . '.php', $directory) ){
            return false;
        }
        if($id){
            self::updateLanguage(self::find($id));
        }
        $langDir = realpath(base_path('resources/lang') . "/en/$file.php");

        $lang = include($langDir);
        foreach($data as $rule => $msg){
            $lang[$rule] = $msg;
        }
        $translation = "<?php \n return \n ". var_export($lang, 1) ."; ?>";
        return  file_put_contents($langDir, $translation);
    }



}
