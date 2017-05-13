<?php

namespace Elazar\Scribing\Filesystem;

class MarkdownFileFilterIterator implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \CallbackFilterIterator(
            new \FilesystemIterator($this->path),
            [$this, 'filter']
        );
    }

    /**
     * @param \SplFileInfo $entry
     * @return boolean
     */
    public function filter(\SplFileInfo $entry)
    {
        return $entry->getType() === 'file'
            && in_array($entry->getExtension(), ['md', 'markdown']);
    }
}
