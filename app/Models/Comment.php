<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'description',
        'blog_id',
        'user_id'
    ];

    public $appends = [
        'human_readable_created_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getHumanReadableCreatedAtAttribute() {
        return $this->created_at->diffForHumans();
    }

}
