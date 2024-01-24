<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Uuid\Uuid;

class DoctorBreakTime extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'doctors_break_times';
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }
    public function doctorAvailable(): HasMany
    {
        return $this->hasMany(DoctorsAvailabilities::class, 'doctors_availabilitie_id');
    }
    public function scheduleTime(): HasMany
    {
        return $this->hasMany(Schedule::class, 'schedule_id');
    }
}
