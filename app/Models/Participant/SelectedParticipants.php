<?php


namespace App\Models\Participant;


use App\Models\AbstractModel;
use Spatie\Translatable\HasTranslations;

class SelectedParticipants extends AbstractModel
{
    use HasTranslations;

    protected $table = 'selected_participants';

    protected $allowed = ["organize_row_id","participant_group_id","bank","hh","director_full_name","name","manufacturer_name","country_of_origin"];
    protected $default = ["organize_row_id","participant_group_id","bank","hh","director_full_name","name","manufacturer_name","country_of_origin"];

    public $translatable = ['bank',"director_full_name"];

}
