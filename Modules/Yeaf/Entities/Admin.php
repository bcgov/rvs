<?php

namespace Modules\Yeaf\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends ModuleModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [


'contact_fname'
,'contact_lname'
,'contact_tele'
,'contact_title'
,'contact_dept'
,'contact_branch'
,'ministry'
,'ministry_branch'
,'ministry_address'
,'ministry_city'
,'ministry_prov'
,'ministry_postal'
,'ministry_tele_victoria'
,'ministry_tele_lower'
,'ministry_tele_toll_free'
,'ministry_TTY_line'
,'ministry_location'
,'ministry_location_city'
,'ministry_location_prov'
,'ministry_location_postal'
,'ministry_location_tele_toll_free'
,'ministry_fax'
,'org'
,'org_fname'
,'org_lname'
,'org_fax'
,'user_fname'
,'user_lname'
,'user_branch'
,'user_tele'
,'user_fax'
,'start_month'
,'start_day'
,'end_month'
,'end_day'
,'temp'
,'application_name'
,'application_abbreviation'];
}
