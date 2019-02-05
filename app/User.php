<?php

namespace App;

use App\Profile;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','password','type'
    ];
    
    // /**
    //  * The attributes excluded from the model's JSON form.
    //  *
    //  * @var array
    //  */
    protected $hidden = [
        'password','created_at','updated_at'
    ];

    public function profile() {
        return $this->hasOne('App\Profile');
    }
    public function photos() {
        return $this->belongsToMany('App\Photo', 'photo_user');
    }
}
