<?php

namespace Elazar\Scribing\Content;

use Elazar\Scribing\Filesystem\FileMetadataParser;
use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use League\CommonMark\Converter;
use Zend\Feed\Writer\Feed;

class FeedContentGenerator
{
    /**
     * @var FileMetadataParser
     */
    private $fileMetadataParser;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @param FileMetadataParser $fileMetadataParser
     * @param Converter $converter
     */
    public function __construct(
        FileMetadataParser $fileMetadataParser,
        Converter $converter
    ) {
        $this->fileMetadataParser = $fileMetadataParser;
        $this->converter = $converter;
    }

    /**
     * @param MarkdownFileFilterIterator $sourceFiles
     * @param string $feedTitle
     * @param string $feedLink
     * @param string $destinationPath
     */
    public function generate(
        MarkdownFileFilterIterator $sourceFiles,
        $feedTitle,
        $feedLink,
        $destinationPath
    ) {
        $posts = $this->getPosts($sourceFiles);

        $feed = new Feed;
        $feed->setId($feedLink);
        $feed->setTitle($feedTitle);
        $feed->setFeedLink($feedLink, 'atom');
        $feed->setDateModified($posts[0]['date']);

        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setId($post['url']);
            $entry->setTitle($post['title']);
            $entry->setLink($post['url']);
            $entry->setDateCreated($post['date']);
            $entry->setDateModified($post['date']);
            $entry->setContent($post['content']);
            $feed->addEntry($entry);
        }

        $path = $destinationPath . '/feed.xml';
        file_put_contents($path, $feed->export('atom'));
    }

    /**
     * @param MarkdownFileFilterIterator $sourceFiles
     * @return FileMetadata[]
     */
    private function getPosts(MarkdownFileFilterIterator $sourceFiles)
    {
        $posts = [];

        foreach ($sourceFiles as $sourceFile) {
            $content = file_get_contents($sourceFile->getPathname());
            $metadata = $this->fileMetadataParser->parse($content);

            $posts[] = [
                'title' => $metadata->getTitle(),
                'date' => $metadata->getDate(),
                'url' => $metadata->getUrl(),
                'content' => $this->converter->convertToHtml($content),
            ];
        }

        usort($posts, function($a, $b) {
            return $a['date']->format('U') - $b['date']->format('U');
        });

        return $posts;
    }
}
