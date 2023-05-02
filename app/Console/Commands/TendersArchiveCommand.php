<?php

namespace App\Console\Commands;

use App\Models\Tender\TenderState;
use App\Models\Tender\TenderStateArchive;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TendersArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:tenders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive ended tenders';

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

        // $old_tenders = DB::connection('mysql2')->table('tender_state')->where('end_date','<',date("Y-m-d H:i:s"))->get();
        $old_tenders = TenderState::where('end_date','<',date("Y-m-d H:i:s"))->get();

        // TenderStateArchive::truncate();

        Log::channel('test')->info(count($old_tenders));

        foreach ($old_tenders as $tender) {
            Log::channel('test')->info($tender->id);
            $exist = TenderStateArchive::find($tender->id);
            if(!$exist){
                $archive_tender = new TenderStateArchive();
                $archive_tender->id = $tender->id;
                $archive_tender->is_archived = $tender->is_archived;
                $archive_tender->title = $tender->title;
                $archive_tender->link = $tender->link;
                $archive_tender->start_date = $tender->start_date;
                $archive_tender->end_date = $tender->end_date;
                $archive_tender->cpv = $tender->cpv;
                $archive_tender->ministry = $tender->ministry;
                $archive_tender->state_institution = $tender->state_institution;
                $archive_tender->regions = $tender->regions;
                $archive_tender->type = $tender->type;
                $archive_tender->tender_type = $tender->tender_type;
                $archive_tender->is_million10 = $tender->is_million10;
                $archive_tender->is_competition = $tender->is_competition;
                $archive_tender->is_new = $tender->is_new;
                $archive_tender->is_closed = $tender->is_closed;
                $archive_tender->estimated = $tender->estimated;
                $archive_tender->estimated_file = $tender->estimated_file;
                $archive_tender->customer_name = $tender->customer_name;
                $archive_tender->password = $tender->password;
                $archive_tender->created_at = $tender->created_at;
                $archive_tender->updated_at = $tender->updated_at;
                $archive_tender->invitation_link = $tender->invitation_link;
                $archive_tender->category = $tender->category;
                $archive_tender->organizer_id = $tender->organizer_id;
                $archive_tender->kind = $tender->kind;
                $archive_tender->procedure_type = $tender->procedure_type;
                $archive_tender->guaranteed = $tender->guaranteed;
                $archive_tender->contract_html = $tender->contract_html;
                $archive_tender->cpv_codes = $tender->cpv_codes;
                $archive_tender->one_person_organize_id = $tender->one_person_organize_id;
                $archive_tender->tender_state_id = $tender->tender_state_id;
                $archive_tender->type_name = $tender->type_name;
                $archive_tender->beneficiari = $tender->beneficiari;
                $archive_tender->manager_id = $tender->manager_id;
                $archive_tender->estimated_price = $tender->estimated_price;
                $archive_tender->participants_count = $tender->participants_count;
                $archive_tender->is_with_model = $tender->is_with_model; 
                $archive_tender->save();
            } else {
                $tender->delete();
            }
        }


        // $old_tenders = DB::connection('mysql2')->table('tender_state')->where('end_date','<',date("Y-m-d H:i:s"))->get();

        // foreach ($old_tenders as $key => $old_tender) {
        //     if($old_tender->invitation_link){
        //         Log::channel('test')->info($key);
        //         TenderStateArchive::where([
        //             ['invitation_link', $old_tender->invitation_link],
        //             ['type_name', $old_tender->type_name],
        //             ['tender_state_id', $old_tender->tender_state_id],
        //         ])->delete();
        //         $archive_tender = new TenderStateArchive();
        //         $archive_tender->id = $old_tender->id;
        //         $archive_tender->is_archived = $old_tender->is_archived;
        //         $archive_tender->title = $old_tender->title;
        //         $archive_tender->link = $old_tender->link;
        //         $archive_tender->start_date = $old_tender->start_date;
        //         $archive_tender->end_date = $old_tender->end_date;
        //         $archive_tender->cpv = $old_tender->cpv;
        //         $archive_tender->ministry = $old_tender->ministry;
        //         $archive_tender->state_institution = $old_tender->state_institution;
        //         $archive_tender->regions = $old_tender->regions;
        //         $archive_tender->type = $old_tender->type;
        //         $archive_tender->tender_type = $old_tender->tender_type;
        //         $archive_tender->is_million10 = $old_tender->is_million10;
        //         $archive_tender->is_competition = $old_tender->is_competition;
        //         $archive_tender->is_new = $old_tender->is_new;
        //         $archive_tender->is_closed = $old_tender->is_closed;
        //         $archive_tender->estimated = $old_tender->estimated;
        //         $archive_tender->estimated_file = $old_tender->estimated_file;
        //         $archive_tender->customer_name = $old_tender->customer_name;
        //         $archive_tender->password = $old_tender->password;
        //         $archive_tender->created_at = $old_tender->created_at;
        //         $archive_tender->updated_at = $old_tender->updated_at;
        //         $archive_tender->invitation_link = $old_tender->invitation_link;
        //         $archive_tender->category = $old_tender->category;
        //         $archive_tender->organizer_id = $old_tender->organizer_id;
        //         $archive_tender->kind = $old_tender->kind;
        //         $archive_tender->procedure_type = $old_tender->procedure_type;
        //         $archive_tender->guaranteed = $old_tender->guaranteed;
        //         $archive_tender->contract_html = $old_tender->contract_html;
        //         $archive_tender->cpv_codes = $old_tender->cpv_codes;
        //         $archive_tender->one_person_organize_id = $old_tender->one_person_organize_id;
        //         $archive_tender->tender_state_id = $old_tender->tender_state_id;
        //         $archive_tender->type_name = $old_tender->type_name;
        //         $archive_tender->beneficiari = $old_tender->beneficiari;
        //         $archive_tender->manager_id = $old_tender->manager_id;
        //         $archive_tender->estimated_price = $old_tender->estimated_price;
        //         $archive_tender->participants_count = $old_tender->participants_count;
        //         $archive_tender->is_with_model = $old_tender->is_with_model; 
        //         $archive_tender->save(); 
        //     } else {
        //         Log::channel('test')->info($old_tender->id);
        //     }
        // }

        // $old_tenders = DB::connection('mysql2')->table('tender_states')->where('end_date','<',date("Y-m-d H:i:s"))->get();

        // $tenders = TenderStateArchive::where('password', 'ՋԳՀ-ԾՁԲ-2023/1')->get();
        // Log::channel('test')->info(count($tenders));

        // $data = [];

        // foreach ($tenders as $archive_tender) {
        //     Log::channel('test')->info($archive_tender->invitation_link);

        //     $tender = DB::connection('mysql2')->table('tender_state')->where('invitation_link', $archive_tender->invitation_link)->first();

        //     if($tender){
        //         Log::channel('test')->info($tender->password);
        //         Log::channel('test')->info($archive_tender->password);
        //         $item = [];
        //         $item['id'] = $tender->id;
        //         $item['is_archived'] = $tender->is_archived;
        //         $item['title'] = $tender->title;
        //         $item['link'] = $tender->link;
        //         $item['start_date'] = $tender->start_date;
        //         $item['end_date'] = $tender->end_date;
        //         $item['cpv'] = $tender->cpv;
        //         $item['ministry'] = $tender->ministry;
        //         $item['state_institution'] = $tender->state_institution;
        //         $item['regions'] = $tender->regions;
        //         $item['type'] = $tender->type;
        //         $item['tender_type'] = $tender->tender_type;
        //         $item['is_million10'] = $tender->is_million10;
        //         $item['is_competition'] = $tender->is_competition;
        //         $item['is_new'] = $tender->is_new;
        //         $item['is_closed'] = $tender->is_closed;
        //         $item['estimated'] = $tender->estimated;
        //         $item['estimated_file'] = $tender->estimated_file;
        //         $item['customer_name'] = $tender->customer_name;
        //         $item['password'] = $tender->password;
        //         $item['created_at'] = $tender->created_at;
        //         $item['updated_at'] = $tender->updated_at;
        //         $item['invitation_link'] = $tender->invitation_link;
        //         $item['category'] = $tender->category;
        //         $item['organizer_id'] = $tender->organizer_id;
        //         $item['kind'] = $tender->kind;
        //         $item['procedure_type'] = $tender->procedure_type;
        //         $item['guaranteed'] = $tender->guaranteed;
        //         $item['contract_html'] = $tender->contract_html;
        //         $item['cpv_codes'] = $tender->cpv_codes;
        //         $item['one_person_organize_id'] = $tender->one_person_organize_id;
        //         $item['tender_state_id'] = $tender->tender_state_id;
        //         $item['type_name'] = $tender->type_name;
        //         $item['beneficiari'] = $tender->beneficiari;
        //         $item['manager_id'] = $tender->manager_id;
        //         $item['estimated_price'] = $tender->estimated_price;
        //         $item['participants_count'] = $tender->participants_count;
        //         $item['is_with_model'] = $tender->is_with_model; 
        //         $data[] = $item;
        //     }
            
        // }

        // Log::channel('test')->info($data);
        // TenderStateArchive::insert($data);
        // Log::channel('test')->info('--------------------------------------------------------------');

        // TenderStateArchive::truncate();

        // Log::channel('test')->info(count($old_tenders));

        // foreach ($old_tenders as $tender) {
        //     $archive_tender = new TenderStateArchive();
        //     $archive_tender->id = $tender->id;
        //     $archive_tender->is_archived = $tender->is_archived;
        //     $archive_tender->title = $tender->title;
        //     $archive_tender->link = $tender->link;
        //     $archive_tender->start_date = $tender->start_date;
        //     $archive_tender->end_date = $tender->end_date;
        //     $archive_tender->cpv = $tender->cpv;
        //     $archive_tender->ministry = $tender->ministry;
        //     $archive_tender->state_institution = $tender->state_institution;
        //     $archive_tender->regions = $tender->regions;
        //     $archive_tender->type = $tender->type;
        //     $archive_tender->tender_type = $tender->tender_type;
        //     $archive_tender->is_million10 = $tender->is_million10;
        //     $archive_tender->is_competition = $tender->is_competition;
        //     $archive_tender->is_new = $tender->is_new;
        //     $archive_tender->is_closed = $tender->is_closed;
        //     $archive_tender->estimated = $tender->estimated;
        //     $archive_tender->estimated_file = $tender->estimated_file;
        //     $archive_tender->customer_name = $tender->customer_name;
        //     $archive_tender->password = $tender->password;
        //     $archive_tender->created_at = $tender->created_at;
        //     $archive_tender->updated_at = $tender->updated_at;
        //     $archive_tender->invitation_link = $tender->invitation_link;
        //     $archive_tender->category = $tender->category;
        //     $archive_tender->organizer_id = $tender->organizer_id;
        //     $archive_tender->kind = $tender->kind;
        //     $archive_tender->procedure_type = $tender->procedure_type;
        //     $archive_tender->guaranteed = $tender->guaranteed;
        //     $archive_tender->contract_html = $tender->contract_html;
        //     $archive_tender->cpv_codes = $tender->cpv_codes;
        //     $archive_tender->one_person_organize_id = $tender->one_person_organize_id;
        //     $archive_tender->tender_state_id = $tender->tender_state_id;
        //     $archive_tender->type_name = $tender->type_name;
        //     $archive_tender->beneficiari = $tender->beneficiari;
        //     $archive_tender->manager_id = $tender->manager_id;
        //     $archive_tender->estimated_price = $tender->estimated_price;
        //     $archive_tender->participants_count = $tender->participants_count;
        //     $archive_tender->is_with_model = $tender->is_with_model; 
        //     $archive_tender->save(); 
        // }

        // TenderState::query()
        // ->where('end_date','<',date("Y-m-d H:i:s"))
        // ->doesntHave('favorite')
        // ->each(function ($oldRecord) {
        //     $newRecord = $oldRecord->replicate();
        //     $newRecord->setTable('tender_state_archive');
        //     $newRecord->save();
        //     $oldRecord->delete();
        // });
    }
}
