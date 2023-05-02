<?php

use App\Models\Cpv\Cpv;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class FixCpvParentIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cpvs = Cpv::all();
        foreach ($cpvs as $cpv) {
            $code = $cpv->code;
            $parent = $this->getParent($code);
            if($parent){
                if($parent['code'] === $code){
                    $cpv->parent_id = 0;
                    $cpv->save();
                } else {
                    $cpv->parent_id = $parent['id'];
                    $cpv->save();
                }
            }
        }
    }

    public function getParent($code)
    {
        $splited_code = str_split($code);
        $lastZeroIndex = 0;
        for ($i=0; $i < count($splited_code); $i++) { 
            if($i !== 0 & $i !== 1 &$splited_code[$i] === '0'){
                $lastZeroIndex = $i - 1;
                break;
            }
        }
        if($lastZeroIndex !== 0 && $lastZeroIndex !== 1){
            for ($i = $lastZeroIndex; $i < count($splited_code); $i++) { 
                $code[$i] = 0;
            }
        } else {
            $code[count($splited_code) - 1] = 0;
        }
        $parent = Cpv::where('code', $code)->first();
        if($parent){
            return $parent;
        } else {
            if($code !== '10000000' && $code !== '20000000' && $code !== '30000000' && $code !== '7000000000'){
                return $this->getParent($code);
            } else {
                return false;
            }
        }
    }
}
