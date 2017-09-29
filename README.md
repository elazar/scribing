# Scribing

Scribing is a simple static blog generator, or [static site
generator](https://davidwalsh.name/introduction-static-site-generators)
designed for generating a blog, written in PHP.

## Installation

Use [Composer](https://getcomposer.org/).

```sh
composer require elazar/scribing
```

## Content

Content for Scribing is written in [Common Markdown](http://commonmark.org).

Scribing supports two document types: posts and pages.

### Posts

Posts include a top level heading containing a title followed by a date
formatted with emphasis.

```markdown
# Post Title

*September 28, 2017*

Content goes here
```

Scribing both builds an individual file for each post and also includes a link
to it on an archive page. Its path is derived from the date and title included
in the Markdown content file.

### Pages

Pages include a top level heading, but unlike posts, they are not
date-specific. Often, their content rarely changes.

```markdown
# Page Title

Content goes here
```

Scribing builds a file for each page at a path based on the name of the
Markdown content file.

## Design

Scribing uses the [Plates](http://platesphp.com) template engine.

When it is run, Scribing requires a template path to be specified. This path
must reference a directory containing two Plates template files: the layout
template and the archive page template.

### Layout Template

`layout.php` is used as the layout template for both posts and pages.

HTML generated from Markdown content is passed into the `$content` variable.

The top level heading is passed into the `$title` variable. It can be used in
the document's `<title>` tag.

### Archive Template

`archive.php` is used to generate the content for the archive page. Unlike the
content for other pages, archive page content is generated from data contained
in post content files rather than being created manually in Markdown.

An associative array of posts keyed by year is passed into `$posts`, where the
value referenced by each year is an enumerated array of posts for that year.
Each post is represented by an associative array containing `'date'`, `'url'`,
and `'title'` keys.

### Common Data

For any common data that needs to be shared between templates, such as for
navigation, create a PHP file that returns an array of data.

```php
return [
    'nav' => [
        '/' => 'Home',
        '/archive' => 'Archive',
        // ...
    ],
];
```

## Generating the Site

To generate the static files for a blog based on the post and page content
files and Plates template files you've created, you use the `scribing` console
command.

### Posts and Pages

`scribing` supports multiple subcommands, two of which are: `build:posts` and
`build:pages`. Both use the same parameters.

```
build:posts [--templateData=path/to/data.php] <sourcePath> <destinationPath> <templatePath>
```

`--templateData` is optional and used to specify a path to a common data file,
if one is needed.

`<sourcePath>` is a directory that contains the source files for posts or pages.

`<destinationPath>` is a directory into which `scribing` will store the files
it generates.

`<templatePath>` is a directory containing the template files.

To build pages instead of posts, simply substitute `build:pages` for
`build:posts` in the example above.

### Feed

To build a feed from posts, `scribing` supports a `build:feed` subcommand.

```
build:feed --feedTitle="Feed Title" --feedLink="https://example.com/feed.xml" <sourcePath> <destinationPath>
```

`--feedTitle` is a title for the feed required by the Atom format

`--feedLink` is an absolute URL to the feed required by the Atom format

`<sourcePath>` is a directory that contains the source files for posts to include in the feed

`<destinationPath>` is a directory into which `scribing` will store the feed file

## Using GitHub Pages

If you are using Scribing to generate a site to be hosted on [GitHub
Pages](https://help.github.com), set up a repository to store your content,
templates, stylesheets, images, etc. that is separate from the repository that
GitHub uses to display the site.

Here's a recommended directory structure:

```
.
├── content
│   ├── pages
│   └── posts
├── images
├── scripts
├── styles
└── templates
```

The purpose of most of the directories above should be self-explanatory.

The `scripts` directory is intended to house build and utility scripts used to
tweak or deploy the files generated by Scribing.

For example, Scribing doesn't handle stylesheet or content license files, so
these have to be manually copied to the build directory.

If you want to [create a custom 404
page](https://help.github.com/articles/creating-a-custom-404-page-for-your-github-pages-site/),
you can author the content as a normal page and then move the generated file to
an appropriate location in your build script like so:

```sh
mv build/404/index.html build/404.html ; rm -fR build/404
```

If you'd like to locate the latest post and use that as the landing page for
the site, you can do it like so:

```sh
LATEST=`find build/ -type f | grep -E '[0-9]+' | grep -v '404' | sort -nr | head -n 1`
cp "$LATEST" build/index.html
```

Here are some related resources for further reading:

* [Securing your GitHub Pages site with HTTPS](https://help.github.com/articles/securing-your-github-pages-site-with-https/)
* [Quick start: Setting up a custom domain](https://help.github.com/articles/quick-start-setting-up-a-custom-domain/)
* [GitHub Pages and Single-Page Apps](https://dev.to/_evansalter/github-pages-and-single-page-apps)
* [GitHub Pages Deployment - Travis CI](https://docs.travis-ci.com/user/deployment/pages/)

## License

The source code for Scribing is licensed under the [MIT License](https://en.wikipedia.org/wiki/MIT_License).
