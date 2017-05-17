<?php

namespace Elazar\Scribing\Filesystem;

use Cocur\Slugify\Slugify;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Inline\Element\Emphasis;
use League\CommonMark\DocParser;

class PostMetadataParser
{
    /**
     * @var DocParser
     */
    private $parser;

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @param DocParser $parser
     * @param Slugify $slugify
     */
    public function __construct(
        DocParser $parser,
        Slugify $slugify
    ) {
        $this->parser = $parser;
        $this->slugify = $slugify;
    }

    /**
     * @param string $content
     * @return PostMetadata
     */
    public function parse($content)
    {
        $walker = $this->parser->parse($content)->walker();
        $title = $date = null;

        while ($event = $walker->next()) {
            $node = $event->getNode();

            if ($node instanceof Heading
                && $node->getLevel() === 1) {
                $title = $node->getStringContent();

            } elseif ($node instanceof Emphasis) {
                $date = new \DateTime($node->firstChild()->getContent());
                break;
            }
        }

        $slugTitle = str_replace("'", '', $title);
        $slug = $this->slugify->slugify($slugTitle);

        return new PostMetadata($title, $slug, $date);
    }
}
