<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PushSchedule extends Model
{
    protected $table = 'push_schedule';
    protected $fillable = ['push_line_id', 'push_msg', 'push_at'];
}
