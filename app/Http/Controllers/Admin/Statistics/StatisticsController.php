<?php

namespace App\Http\Controllers\Admin\Statistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Cpv\Cpv;
use App\Models\Cpv\Specifications;
use App\Models\Statistics\CpvStatistics;
use App\Models\Statistics\CpvStatisticsParticipants;
use Illuminate\Support\Facades\Log;

class StatisticsController extends AbstractController
{
    public function mergeCpvStatistics(Request $request)
    {
        $data = $request->all();

        CpvStatistics::where('specification_id', $data['selectedSpecification']['id'])->update(['specification_id' => $data['selectedMainSpecification']['id']]);

        return $data;
    }
    /**
     * Create CPV Statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setCpvStatistics(Request $request){
        $data = $request->all();
        $cpv_statistics = new CpvStatistics();

        $cpv_statistics->tender_state_cpv_id = $data['tender_state_cpv_id'];
        $cpv_statistics->cpv_id = $data['cpv_id'];
        $cpv_statistics->region_id = $data['region_id'];
        $cpv_statistics->unit_id = $data['unit_id'];

        if($data['specification_id']){
            $specification_id = $data['specification_id'];
        } else {
            $new_specification = new Specifications();
            $new_specification->setTranslation('description', 'hy' , $data['specification']);
            $new_specification->cpv_id = $data['cpv_id'];
            $new_specification->users_id = 0;
            $new_specification->save();
            $specification_id = $new_specification->id;
        }

        $cpv_statistics->specification_id = $specification_id;
        $cpv_statistics->specification = $data['specification'];
        $cpv_statistics->count = $data['count'];
        $cpv_statistics->winner_get_date = $data['winner_get_date'];

        $cpv_statistics->established = $data['established'];
        $cpv_statistics->failed_substantiation = $data['failed_substantiation'];

        $cpv_statistics->estimated_price = $data['estimated_price'];
        $cpv_statistics->estimated_price_unit = $data['estimated_price'] / $data['count'];
        $cpv_statistics->save();

        if(isset($data['participants'])){
            foreach($data['participants'] as $participant) {
                $cpv_statistics_participant = new CpvStatisticsParticipants();
                $cpv_statistics_participant->cpv_statistics_id = $cpv_statistics->id;
                $cpv_statistics_participant->name = $participant['name'];
                $cpv_statistics_participant->value = $participant['value'];
                $cpv_statistics_participant->vat = $participant['vat'];
                $cpv_statistics_participant->total = $participant['total'];
                $cpv_statistics_participant->total_unit = $participant['total'] / $data['count'];
                if(isset($participant['is_winner'])){
                    $cpv_statistics_participant->is_winner = $participant['is_winner'] === 'true';
                }
                $cpv_statistics_participant->save();
            }
        }

        return $cpv_statistics;
    }

    /**
     * Update CPV Statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateCpvStatistics(Request $request, int $id){
        $data = $request->all();
        $cpv_statistics = CpvStatistics::find($id);

        $cpv_statistics->tender_state_cpv_id = $data['tender_state_cpv_id'];
        $cpv_statistics->cpv_id = $data['cpv_id'];
        $cpv_statistics->region_id = $data['region_id'];
        $cpv_statistics->unit_id = $data['unit_id'];

        if($data['specification_id']){
            $specification_id = $data['specification_id'];
        } else {
            $new_specification = new Specifications();
            $new_specification->setTranslation('description', 'hy' , $data['specification']);
            $new_specification->cpv_id = $data['cpv_id'];
            $new_specification->users_id = 0;
            $new_specification->save();
            $specification_id = $new_specification->id;
        }

        $cpv_statistics->specification_id = $specification_id;
        $cpv_statistics->specification = $data['specification'];
        $cpv_statistics->count = $data['count'];
        $cpv_statistics->winner_get_date = $data['winner_get_date'];

        $cpv_statistics->established = $data['established'];
        $cpv_statistics->failed_substantiation = $data['failed_substantiation'];

        $cpv_statistics->estimated_price = $data['estimated_price'];
        $cpv_statistics->estimated_price_unit = $data['estimated_price'] / $data['count'];
        $cpv_statistics->save();

        CpvStatisticsParticipants::where('cpv_statistics_id', $cpv_statistics->id)->delete();

        if(isset($data['participants'])){
            foreach($data['participants'] as $participant) {
                $cpv_statistics_participant = new CpvStatisticsParticipants();
                $cpv_statistics_participant->cpv_statistics_id = $cpv_statistics->id;
                $cpv_statistics_participant->name = $participant['name'];
                $cpv_statistics_participant->value = $participant['value'];
                $cpv_statistics_participant->vat = $participant['vat'];
                $cpv_statistics_participant->total = $participant['total'];
                $cpv_statistics_participant->total_unit = $participant['total'] / $data['count'];
                if(isset($participant['is_winner'])){
                    $cpv_statistics_participant->is_winner = $participant['is_winner'] === 'true';
                }
                $cpv_statistics_participant->save();
            }
        }

        return $cpv_statistics;
    }


    /**
     * Update CPV Statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCpvStatistics(Request $request, int $specification_id){
        $cpv_statistics = CpvStatistics::with('participants')
                ->where('specification_id', $specification_id)
                ->whereDate("winner_get_date", ">=", $request->get('startDate'))
                ->whereDate("winner_get_date", "<=", $request->get('endDate'))
                ->where('unit_id', $request->get('unit'));

        if($request->get('region')){
            return $cpv_statistics->where('region_id', $request->get('region'))->get();
        } else {
            return $cpv_statistics->get();
        }
    }


    /**
     * Set CPV Potential Statistics.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setCpvPotential(Request $request, int $cpv_id){
        $cpv = Cpv::findOrFail($cpv_id);
        $cpv->potential_paper = $cpv->potential_paper + $request->get('potentialValue');
        $cpv->save();
        return $cpv;
    }

    /**
     * Serach participant name.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function searchParticipantName(Request $request){
        $data = $request->all();

        $query = $data['query'];

        $result = CpvStatisticsParticipants::where('name', 'LIKE', "$query%")->groupBy('name')->get();

        return $result;
    }

    
    
    
    
}
