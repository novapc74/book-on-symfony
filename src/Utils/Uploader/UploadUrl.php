<?php

namespace App\Utils\Uploader;

use App\Utils\Uploader\UploaderInterface;

class UploadUrl implements UploaderInterface
{
    /**
     * @var string $path
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $this->path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

    /**
     * @var    string $content
     * @param  string $pathToContent
     * @return array
     */
    public function toArray(): array
    {
        $content = $this->getContent();
        
        return json_decode($content, true);
    }
}
