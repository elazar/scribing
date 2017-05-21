<?php

namespace Elazar\Scribing\Filesystem;

use DateTime;

class FileMetadata
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @param string $title
     * @param string $slug
     * @param DateTime $date
     */
    public function __construct($title, $slug, DateTime $date = null)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return sprintf(
            '/%s/%s',
            $this->date->format('Y/m/d'),
            $this->slug
        );
    }
}
