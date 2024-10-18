<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class FollowUpComment extends Model

{

    use HasFactory,SoftDeletes;



    protected $guarded = ['id'];

    public function commentFileDetail(){

        return $this->hasMany(FollowUpCommentFile::class,'comment_id');
    }
    public function userDetail(){

        return $this->hasOne(User::class,'id','user_id');
    }
}

