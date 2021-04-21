<?php

namespace Carrot;

class FilesystemHelper {

  private string $export_dir;

  public function __construct($export_dir)
  {
    $this->export_dir = $export_dir;
  }

  /**
   *  Creates basic folder structure in 'publish' directory
   * */
  public function create_directories($dirs): void 
  {
    echo "Creating filesystem structure...\n";

    foreach ($dirs as $dir) {
      $this->create_dir($dir);
    }
  }

  /**
   *  @codeCoverageIgnore
   *  Creates a single direcotry if not already there
   */
  private function create_dir($dir_name): void 
  {
    if (!file_exists($dir_name)) {
      try {
        mkdir($dir_name);
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }
  }

  public function create_post($post_data): void
  {
    $target_path = $this->export_dir . '/' . $post_data['slug'];
    $this->create_dir($target_path);

    $target_file = $target_path . '/' . 'index.html';
    file_put_contents($target_file, $post_data['markup']);
  }

  public function copy_dir($source, $destination): void
  {
    $this->create_dir($destination);
    $files = scandir($source);
    foreach ($files as $file) {
      if ($file != "." && $file != "..") copy("$source/$file", "$destination/$file");
    }
  }
}
