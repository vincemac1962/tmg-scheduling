<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'file',
        'created_by',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function upload()
    {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }


    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function booted()
    {
        static::deleting(function ($scheduleItem) {
            $scheduleItem->upload()->delete();
        });
    }
}
