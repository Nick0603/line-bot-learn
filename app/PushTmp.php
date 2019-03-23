<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PushTmp extends Model
{
    protected $table = 'push_tmp';
    protected $fillable = ['push_list_id', 'group_id', 'push_msg', 'push_token', 'push_at'];
}
