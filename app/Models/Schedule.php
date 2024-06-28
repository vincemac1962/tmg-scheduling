<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
    ];

    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class);
    }
}
