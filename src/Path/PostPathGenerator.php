<?php

namespace Elazar\Scribing\Path;

use Elazar\Scribing\Filesystem\FileMetadataParser;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Inline\Element\Emphasis;

class PostPathGenerator implements PathGenerator
{
    /**
     * @var FileMetadataParser
     */
    private $parser;

    /**
     * @param FileMetadataParser $parser
     */
    public function __construct(
        FileMetadataParser $parser
    ) {
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     */
    public function generate($sourceFile)
    {
        $content = file_get_contents($sourceFile);
        $metadata = $this->parser->parse($content);
        return ltrim($metadata->getUrl(), '/') . '/index.html';
    }
}
