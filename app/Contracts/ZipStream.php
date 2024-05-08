<?php
namespace App\Contracts;

use DateTimeInterface;
use ZipStream\CompressionMethod;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method void addFileFromStream(string $fileName, $stream, string $comment = '', ?CompressionMethod $compressionMethod = null, ?int $deflateLevel = null, ?DateTimeInterface $lastModificationDateTime = null, ?int $maxSize = null, ?int $exactSize = null, ?bool $enableZeroHeader = null)
 * @method int finish()
 */
interface ZipStream
{
    public function setOutputFilename(string $filename): ZipStream;

    /**
     * Return a streamed zip file
     *
     * @return StreamedResponse
     */
    public function stream(): StreamedResponse;

    public function add($sourceStream, $filename): ZipStream;
}