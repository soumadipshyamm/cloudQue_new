<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Uuid\Uuid;

class DoctorsAvailabilities extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }
    public function schedules():BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
    public function doctorBreakTime():HasMany
    {
        return $this->hasMany(DoctorBreakTime::class, 'doctors_availabilitie_id');
    }
}
