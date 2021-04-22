<p align="center"><img alt="Carrot" src="https://github.com/karate/carrot/blob/master/resources/banner.png?raw=true" width="301" /></p>

## What is this?
This is one of the simplest static site generators (SSG) out there.
It can be used to create a really simple blog.

## Why another SSG
I was looking for a blog-oriented SSG, and after some research I decided to
build my own, in order to explore php's best practices and desing patterns.

If you really want a mature SSG with decent features, you can continue searching.

## What does Carrot do?
Like most SSGs, it will read a directory of markup files,
each one containing a blog post, and it will build a static site with an
index page and one page per blog post.

Every blog post has a title, a url, a date and it's markdown content.
You have the option to make it a menu item, wich means that it will not
be visible in the index page, but it will be shown in the site's menu
(useful for pages like About me, Contact etc).

## Features
- [x] Php 7/8 with strict mode.
- [x] Extended markdown with yaml metadata [metaparsedown](https://github.com/pagerange/metaparsedown)
- [x] Unit testing, 100% coverage in core classes and methods
- [x] Main menu
- [ ] Tags / Topics per post
- [ ] Image manipulation (resize, optimize, thumbnails etc)

## Usage
Clone the repo
```
git clone https://github.com/karate/carrot.git
cd carrot
composer install
```

Edit settigns
```
source/settings.yml
```

Create blog posts, copy the format of `source/posts/test-post.md`

Build your blog
```
composer build
```

or setup a test server for preview (this will build the site as well)
```
composer serve
```

Deploy your site to your server by uploading the `publish` directory

## Contribute
This is a very light program by design, and I'm only planning to only
include features that I'm going to use. You're more than welcome to report issues
and bugs by creating Issues. If you want to contribute with code, please create an
issue before any PRs.
