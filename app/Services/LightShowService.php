<?php
namespace App\Services;

use App\Models\LightShow;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Contracts\ZipStream;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Contracts\LightShowService as LightShowServiceContract;

class LightShowService implements LightShowServiceContract
{
    public function __construct(private Request $request, private ZipStream $zip) {}

    public function prune($hours)
    {
        foreach (Storage::disk('temp')->files() as $file) 
        {
            $fileLastModified = Carbon::createFromTimestamp(Storage::disk('temp')->lastModified($file));
 
            if ($fileLastModified->addHours($hours)->isPast())
            {
                Storage::disk('temp')->delete($file);
            }
        }
    }

    private function getNaturalFilename($filename, $title)
    {
        return Str::headline($title) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function downloadSequence(LightShow $light_show): StreamedResponse
    {
        $light_show->markDownloaded();

        return Storage::download($light_show->sequence_file, $this->getNaturalFilename($light_show->sequence_file, $light_show->title));
    }

    public function downloadAudio(LightShow $light_show): StreamedResponse
    {
        return Storage::download($light_show->audio_file, $this->getNaturalFilename($light_show->audio_file, $light_show->title));
    }

    public function downloadZip(LightShow $light_show): StreamedResponse
    {
        $light_show->markDownloaded();

        return $this->zip
                    ->setOutputFilename(Str::headline($light_show->title) . '.zip')
                    ->add(Storage::readStream($light_show->audio_file), $this->getNaturalFilename($light_show->audio_file, $light_show->title))
                    ->add(Storage::readStream($light_show->sequence_file), $this->getNaturalFilename($light_show->sequence_file, $light_show->title))
                    ->stream();
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