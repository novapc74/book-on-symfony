<?php

namespace App\Utils\CategoryMaker;

use Symfony\Component\Mime\MimeTypes;

class SaveBookImage
{
    /**
     * @var string $dir
     */
    private $dir = __DIR__ . "/../../../assets/images";

    /**
     * @var string $defaultImage
     */
    private $defaultImage = __DIR__ . "/../../../assets/defaultImage/default-book.jpg";

    /**
     * @var string[] $replaceableElements
     */
    private $replaceableElements = [
        ' ',
        ',',
        '/',
        '#',
        "!",
        ".",
    ];

    /**
     * @var mixed $pathToImage
     */
    private $pathToImage;

    /**
     * @var \App\Entity\Book $book
     */
    private $book;

    /**
     * @var mixed $nameImage
     */
    private $nameImage;

    /**
     * @param \App\Entity\Book $book
     */
    public function __construct(\App\Entity\Book $book)
    {
        $this->book = $book;
    }

    public function add(): void
    {
        $path = $this->makePath();
        $imageName = pathinfo($path)['basename'];
        $this->setNameImage($imageName);

        if (is_file($this->dir . "/{$imageName}") === false) {
            $image = $this->getImage();
            $this->setPath($path);

            if (is_dir($this->dir) === false) {
                mkdir($this->dir);
            }
    
            file_put_contents($path, $image);
        }
    }

    private function getImage()
    {
        $thumbnailUrl = $this->book->getThumbnailUrl();

        $content = new \App\Utils\Uploader\UploadUrl($thumbnailUrl);
        $image = $content->getContent();

        if ($image == null || strlen($image) == 0) {
            $image = file_get_contents($this->defaultImage);
        }
        
        return $image;
    }

    private function makePath(): string
    {
        $title = $this->book->getTitle();
        $url = $this->book->getThumbnailUrl();

        $extension = pathinfo($url)['extension'];
        $nameImage = str_replace($this->replaceableElements, '_', $title);

        return "{$this->dir}/{$nameImage}.{$extension}"; 
    }

    private function setPath(string $path): void
    {
        $this->pathToImage = $path;
    }

    private function setNameImage($nameImage)
    {
        $this->nameImage = $nameImage;
    }

    public function getNameImage()
    {
        return $this->nameImage;
    }
}