<?php

namespace App\Utils\Uploader;

interface UploaderInterface
{
    public function getContent();

    public function toArray();
}