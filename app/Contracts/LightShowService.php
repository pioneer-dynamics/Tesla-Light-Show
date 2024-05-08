<?php
namespace App\Contracts;

use App\Models\LightShow;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface LightShowService
{
    /**
     * Download sequence file and mark it is downloaded
     * 
     * @param  LightShow  $light_show
     * 
     * @return StreamedResponse
     */
    public function downloadSequence(LightShow $light_show): StreamedResponse;
    
    /**
     * Download audio file
     * 
     * @param  LightShow  $light_show
     * 
     * @return StreamedResponse
     */
    public function downloadAudio(LightShow $light_show): StreamedResponse;

    /**
     * Download zip file and mark as downloaded
     * 
     * @param  LightShow  $light_show
     * 
     * @return StreamedResponse
     */
    public function downloadZip(LightShow $light_show): StreamedResponse;

    /**
     * Create new light show
     * 
     * @param  array  $data
     * 
     * @return LightShow
     */
    public function create(array $data): LightShow;

    /**
     * Update light show
     * 
     * @param  LightShow  $light_show
     * @param  array  $data
     * 
     * @return bool
     */
    public function update(LightShow $light_show, array $data): bool;

    /**
     * Delete light show
     * 
     * @param  LightShow  $light_show
     * 
     * @return bool
     */
    public function delete(LightShow $light_show): bool;

    /**
     * Mark light show as verified
     * 
     * @param  LightShow  $light_show
     * 
     * @return bool
     */
    public function markAsVerified(LightShow $light_show): bool;

    /**
     * Report light show
     * 
     * @param  LightShow  $lightShow
     * 
     * @return bool
     */
    public function report(LightShow $lightShow): bool;
}