<?php

namespace App\Http\Requests;

use App\Rules\ValidateBackgroundFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as UploadedFile;
use Illuminate\Foundation\Http\FormRequest;

class StoreLightShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'duration' => $this->parseDuration()
        ]);
    }

    private function parseDuration()
    {
        return rescue(function() {
            $parts = explode(':', $this->duration);
            $minutes = (int) $parts[0];
            $seconds = (int) $parts[1];

            if ($minutes < 10) {
                $minutes = "0" . $minutes;
            }

            return $minutes . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
        }) ?? $this->duration;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|regex:/^[0-5][0-9]:[0-5][0-9]$/',
            'sequence_file' =>  [
                'required',
                'string',
            ],
            'audio_file' => [
                'required',
                'string',
            ],
            'video_preview' => [
                'required_without:youtube_preview',
                'nullable',
                'string',
            ],
            'youtube_preview' => 'required_without:video_preview|nullable|url|starts_with:https://www.youtube.com/watch?v=|unique:light_shows,video_preview',
        ];
    }
}
