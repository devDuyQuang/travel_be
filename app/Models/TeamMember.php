<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'job_title',
        'department',
        'avatar',
        'phone',
        'email',
        'zalo_url',
        'facebook_url',
        'linkedin_url',
        'short_description',
        'sort_order',
        'show_on_team',
        'show_in_quick_panel',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'show_on_team' => 'boolean',
        'show_in_quick_panel' => 'boolean',
        'is_active' => 'boolean',
    ];
}
