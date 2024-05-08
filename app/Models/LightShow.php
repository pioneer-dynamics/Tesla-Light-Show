<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use mathewparet\LaravelPolicyAbilitiesExport\Traits\ExportsPermissions;

class LightShow extends Model
{
    use HasFactory;
    use HasHashId;
    use ExportsPermissions;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'user_id',
        'sequence_file',
        'audio_file',
        'video_preview',
        'youtube_preview',
    ];

    protected $casts = [
        'is_reported' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'verified', 'has_not_been_reported', 'video_embed_url'
    ];

    public function markDownloaded()
    {
        $this->downloads++;

        $this->save();
    }

    public static function booted()
    {
        static::deleting(function ($lightShow) {
            Storage::delete($lightShow->sequence_file);
            Storage::delete($lightShow->audio_file);
        });
    }

    public function hasNotBeenReported(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_reported === false
        );
    }

    public function videoEmbedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if($this->youtube_preview)
                {
                    $parsedUrl = parse_url($this->youtube_preview);
                
                    parse_str($parsedUrl['query'], $queryParams);
                    
                    return 'https://www.youtube.com/embed/' . $queryParams['v'];
    
                }
                else
                {
                    return Storage::url($this->video_preview);
                }
            }
        );
    }

    public function report()
    {
        return $this->forceFill(['is_reported' => true])->save();
    }
    
    public function unReport()
    {
        return $this->forceFill(['is_reported' => false])->save();
    }

    public function verified(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->verified_at !== null
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactors()
    {
        return $this->hasMany(Reaction::class);
    }
}
