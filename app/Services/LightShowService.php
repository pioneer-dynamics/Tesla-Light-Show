<?php
namespace App\Services;

use App\Models\LightShow;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Contracts\LightShowService as LightShowServiceContract;

class LightShowService implements LightShowServiceContract
{
    public function __construct(private Request $request) {}

    public function downloadSequence(LightShow $light_show): StreamedResponse
    {
        $light_show->markDownloaded();

        return Storage::download($light_show->sequence_file, Str::headline($light_show->title) . '.' . pathinfo($light_show->sequence_file, PATHINFO_EXTENSION));
    }

    public function downloadAudio(LightShow $light_show): StreamedResponse
    {
        return Storage::download($light_show->audio_file, Str::headline($light_show->title) . '.' . pathinfo($light_show->audio_file, PATHINFO_EXTENSION));
    }

    public function downloadZip(LightShow $light_show): StreamedResponse
    {
        return new StreamedResponse();
    }

    public function create(array $data): LightShow
    {
        Storage::move(Storage::disk('temp')->path($data['sequence_file']), Storage::path($data['sequence_file']));
        Storage::move(Storage::disk('temp')->path($data['audio_file']), Storage::path($data['audio_file']));
        
        if($data['video_preview'])
            Storage::move(Storage::disk('temp')->path($data['video_preview']), Storage::path($data['video_preview']));

        return $this->request->user()->lightShows()->create($data);
    }

    public function update(LightShow $light_show, array $data): bool
    {
        if($data['sequence_file'])
        {
            Storage::delete($light_show->sequence_file);

            Storage::move(Storage::disk('temp')->path($data['sequence_file']), Storage::path($data['sequence_file']));

            $light_show->sequence_file = $data['sequence_file'];
        }
        
        if($data['audio_file'])
        {
            Storage::delete($light_show->audio_file);

            Storage::move(Storage::disk('temp')->path($data['audio_file']), Storage::path($data['audio_file']));

            $light_show->audio_file = $data['audio_file'];
        }
       
        if($data['video_preview'])
        {
            Storage::delete($light_show->audio_file);

            Storage::move(Storage::disk('temp')->path($data['video_preview']), Storage::path($data['video_preview']));

            $light_show->video_preview = $data['video_preview'];
        }

        return $light_show->fill(Arr::except($data, ['sequence_file', 'audio_file', 'video_preview']))->save();
    }

    public function delete(LightShow $light_show): bool
    {
        return $light_show->delete();
    }

    public function markAsVerified(LightShow $light_show): bool
    {
        return $light_show->forceFill(['verified_at' => now()])->save();
    }

    public function report(LightShow $lightShow): bool
    {
        return $lightShow->forceFill(['is_reported' => true])->save();
    }
}