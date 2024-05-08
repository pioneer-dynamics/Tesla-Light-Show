<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLightShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'duration' => 'required|regex:/^[0-9]{2}:[0-9]{2}$/',
            'sequence_file' =>  [
                'nullable',
                'string',
            ],
            'audio_file' => [
                'nullable',
                'string',
            ],
            'video_preview' => 'nullable|url|starts_with:https://www.youtube.com/watch?v=|unique:light_shows,video_preview,'.$this->lightShow->id,
        ];
    }
}
