<?php

namespace App\Services\Participant;

use App\Models\Organize\Organize;
use App\Models\Organize\OrganizeItender;
use App\Models\Organize\OrganizeOnePerson;
use App\Models\Organize\OrganizeRow;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Participant\ParticipantRow;
use App\Models\User\User;
use Illuminate\Support\Facades\Log;

class ParticipantRowService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;

    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function createParticipantRow(): void
    {
        $this->builder();
    }

    public function checkEqualPrice($row_id){
        if (isset($this->request->total_price)){
            $offers = ParticipantRow::where([
                ['organize_row_id', '=', $row_id],
                ['total_price', '=', $this->request->total_price],
                ['participant_id', '!=', auth('api')->user()->id],
            ])->get();
            return boolval(count($offers));
        } else {
            $organize_row = OrganizeRow::find($row_id);
            $organizeItender = OrganizeItender::find($organize_row->organize_id);
            if($organizeItender){
                $user = User::find($organizeItender->user_id);
                $with_vat = $user->vat_payer_type === 'not_payer' ? false : true;
                if($with_vat){
                    $offers = ParticipantRow::where([
                        ['organize_row_id', '=', $row_id],
                        ['value', '=', $this->request->price],
                        ['participant_id', '!=', auth('api')->user()->id],
                    ])->get();
                    return boolval(count($offers));
                } else {
                    $offers = ParticipantRow::where([
                        ['organize_row_id', '=', $row_id],
                        ['cost', '=', $this->request->price],
                        ['participant_id', '!=', auth('api')->user()->id],
                    ])->get();
                    return boolval(count($offers));
                }
            } else {
                $offers = ParticipantRow::where([
                    ['organize_row_id', '=', $row_id],
                    ['cost', '=', $this->request->price],
                    ['participant_id', '!=', auth('api')->user()->id],
                ])->get();
                return boolval(count($offers));
            }
        }
    }

    public function updateParticipantRow(int $id): void
    {
        $participant = ParticipantRow::findOrFail($id);
        if ($this->request->cost) {
            $participant->cost = $this->request->cost;
        }
        if ($this->request->profit) {
            $participant->profit = $this->request->profit;
        }
        if ($this->request->value) {
            $participant->value = $this->request->value;
        }
        if ($this->request->vat) {
            $participant->vat = $this->request->vat;
        }
        $participant->new_value = $this->request->new_value ? $this->request->new_value : null;
        if ($this->request->get_response) {
            $participant->get_response = $this->request->get_response;
        }
        if ($this->request->canceled_contract_request) {
            $participant->canceled_contract_request = $this->request->canceled_contract_request;
        }
        $participant->save();
    }

    public function builder(): void
    {
        ParticipantRow::insert($this->request->all());
    }

}
