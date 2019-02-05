<?php namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $fillable = ['user_id','f_name','l_name','age','gender','location'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user() {
        return $this->belongsTo('App\user');
    }
}
