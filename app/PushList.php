<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PushList extends Model
{
    protected $table = 'push_list';
    protected $fillable = ['push_list_id', 'group_id', 'push_msg', 'push_token', 'push_at'];
}
