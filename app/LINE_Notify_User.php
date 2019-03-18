<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class LINE_Notify_User extends Model
{
    protected $table = 'line_users';
    protected $fillable = ['access_token', 'targetType', 'target', 'sid', 'email'];
    public static function getAllToken()
    {
        return static::all()->pluck('access_token')->toArray();
    }
}
