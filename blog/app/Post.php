<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'posts';
    protected $fillable = array('url', 'title', 'description', 'content', 'image', 'blog', 'category_id');

    public static function prevBlogPostUrl($id){
    	$blog = static::where('id', '<', $id)->orderBy('id', 'desc')->first();
    	return $blog? $blog->url : "#";
    }

    public function tags(){
    	return $this->belongsToMany('App\BlogTag', 'blog_post_tags', 'post_id', 'tag_id');
    }
}
