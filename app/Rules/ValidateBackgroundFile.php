<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateBackgroundFile implements ValidationRule
{
    public function __construct(private string $maxSize, private array $extensions)
    {
        
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Storage::disk('temp')->exists($value)) {
            $fail('The file does not exist.');
        }
 
        if (!in_array(Storage::disk('temp')->mimeType($value), $this->extensions, true)) {
            $fail('Invalid file.');
        }
    }
}
