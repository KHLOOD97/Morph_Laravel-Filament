<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',  // Add other attributes as needed
        'content',
        'tags',
        'published', // Assuming a foreign key for the user who created the post
        // Add other attributes as needed
    ];
    protected $casts = [
        'tags' => 'array',
    ];
 public function authors()
 {
return $this->BelongsToMany(User::class,'post_user')->withTimestamps();

 }
 public function comments()
 {
return $this->morphMany(Comments::class,'commentable');

 }
 
}




