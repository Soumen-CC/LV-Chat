<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Course extends Model
{

    protected $fillable = ['admin_id', 'category_id','language_id', 'title', 'status', 'slug', 'short_content', 'content', 'what_you_will_learn', 'requirements', 'image_name', 'icon', 'popular_course'];
    
    function admins(){
        return $this->hasOne('App\Model\Admin', 'id', 'admin_id')->select(['username', 'id', 'image_name', 'yourself']);
    }
    
    public function userCourses() {
       return $this->belongsToMany(User::class);
    }
    
    function enrolledStudents(){
        return $this->belongsToMany(User::class)->select('users.id', 'name');
    }

}
