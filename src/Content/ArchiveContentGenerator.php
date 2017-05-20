<?php

namespace Elazar\Scribing\Content;

use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use Elazar\Scribing\Filesystem\PostMetadataParser;
use League\Plates\Engine;

class ArchiveContentGenerator
{
    /**
     * @var PostMetadataParser
     */
    private $postMetadataParser;

    /**
     * @param PostMetadataParser $postMetadataParser
     */
    public function __construct(
        PostMetadataParser $postMetadataParser
    ) {
        $this->postMetadataParser = $postMetadataParser;
    }

    /**
     * @param MarkdownFileFilterIterator $sourceFiles
     * @param Engine $engine
     * @param string $destinationPath
     * @param PathGenerator $pathGenerator
     */
    public function generate(
        MarkdownFileFilterIterator $sourceFiles,
        Engine $engine,
        $destinationPath
    ) {
        $posts = $this->getPosts($sourceFiles);
        $content = $engine->render('archive', [
            'title' => 'Archive',
            'posts' => $posts,
        ]);
        $generated = $engine->render('layout', ['content' => $content]);
        $dirPath = $destinationPath . '/archive';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        $path = $dirPath. '/index.html';
        file_put_contents($path, $generated);
    }

    /**
     * @param MarkdownFileFilterIterator $sourceFiles
     * @return PostMetadata[]
     */
    private function getPosts(MarkdownFileFilterIterator $sourceFiles)
    {
        $posts = [];

        foreach ($sourceFiles as $sourceFile) {
            $content = file_get_contents($sourceFile->getPathname());
            $metadata = $this->postMetadataParser->parse($content);

            $date = $metadata->getDate();
            $year = $date->format('Y');
            if (!isset($posts[$year])) {
                $posts[$year] = [];
            }

            $posts[$year][$date->format('md') . '-' . $metadata->getTitle()] = [
                'title' => $metadata->getTitle(),
                'date' => $date->format('M j'),
                'url' => $metadata->getUrl(),
            ];
        }

        // Sort posts in reverse order by year
        krsort($posts);

        // Sort posts within each year in reverse order by month and day
        array_walk($posts, function(&$posts) {
            krsort($posts);
            $posts = array_values($posts);
        });

        return $posts;
    }
}
