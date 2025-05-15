<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = true;
    protected $fillable = ['key','value'];

    public static function get($key, $default = null)
    {
        if ($s = static::where('key', $key)->first()) {
            return $s->value;
        }
        return $default;
    }
    public static function set($key, $value)
    {
        return static::updateOrCreate(['key'=>$key], ['value'=>$value]);
    }
}
