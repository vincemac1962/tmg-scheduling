<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_ref',
        'site_name',
        'site_active',
        'site_address',
        'site_postcode',
        'site_tel',
        'site_email',
        'site_contact',
        'site_notes'
    ];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_site', 'site_id', 'schedule_id')
            ->withPivot('downloaded', 'downloaded_at');
    }
}