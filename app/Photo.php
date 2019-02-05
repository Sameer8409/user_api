<?php namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model {

    protected $fillable = ['image_name'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];


    // Relationships
    public function user() {
        return $this->belongsToMany('App\User', 'photo_user');
    }
}
