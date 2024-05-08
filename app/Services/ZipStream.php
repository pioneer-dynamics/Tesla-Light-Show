<?php
namespace App\Services;

use ZipStream\ZipStream as ZipStreamZipStream;
use App\Contracts\ZipStream as ZipStreamContract;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method void addFileFromStream(string $fileName, $stream, string $comment = '', ?CompressionMethod $compressionMethod = null, ?int $deflateLevel = null, ?DateTimeInterface $lastModificationDateTime = null, ?int $maxSize = null, ?int $exactSize = null, ?bool $enableZeroHeader = null)
 * @method int finish()
 */
class ZipStream implements ZipStreamContract
{
    private $outputFilename = null;

    private $zip;

    private $files = [];

    public function add($sourceStream, $filename): ZipStream
    {
        $this->files[] = [
            'source' => $sourceStream,
            'destination' => $filename
        ];

        return $this;
    }

    public function setOutputFilename($filename): ZipStream
    {
        $this->outputFilename = $filename;

        return $this;
    }

    private function zipStreamObject()
    {
        return $this->zip ?? $this->zip = new ZipStreamZipStream(outputName: $this->outputFilename);
    }

    public function stream(): StreamedResponse
    {
        $this->outputFilename = $outputFilename ?? $this->outputFilename;

        return response()->streamDownload(function () {
            foreach($this->files as $file)
            {
                $this->zipStreamObject()->addFileFromStream($file['destination'], $file['source']);
            }

            $this->zipStreamObject()->finish();
        });
    }

    public function __call($name, $arguments)
    {
        return $this->zipStreamObject()->{$name}(...$arguments);
    }
}