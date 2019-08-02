<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DayTask extends Model
{
    const STATUS_WAIT = 'wait';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';

    const TYPE_PLAN = 'plan';
    const TYPE_NON_PLAN = 'non-plan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day_id', 'title', 'status', 'type'
    ];
}
