<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PushList extends Model
{
    protected $table = 'push_list';
    protected $fillable = ['push_schedule_id','push_line_id', 'push_msg', 'push_token', 'push_at'];

    public function line_user(){
        return $this->hasOne('App\LINE_Notify_User','id','push_line_id');
    }
}
