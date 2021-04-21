<?php

namespace Carrot;

class Reader {
  private array $posts;

   /**
    * Read the source directory and get all files (posts)
    */
  public function __construct($source_directory)
  {
    echo "Reading posts...\n";
    $posts = scandir($source_directory);
    // Ignore hidden files (begining with .)
    foreach ($posts as $idx => $post) {
      if(strpos($post, '.') === 0) {
        unset($posts[$idx]);
      }
    }
    $this->posts = $posts;
  }

  public function get_post_filenames(): array
  {
    return $this->posts;
  }
}
