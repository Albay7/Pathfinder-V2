<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistDislike extends Model
{
    protected $fillable = [
        'user_id',
        'skill',
        'playlist_url',
        'playlist_label'
    ];

    /**
     * Get the user who disliked this playlist
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
