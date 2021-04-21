<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers \Carrot\FilesystemHelper
 * @uses \Carrot\Settings
 */
final class FilesystemHelperTest extends TestCase
{
  private string $test_slug = 'test-slug-01';
  private string $test_markup = 'Test markup <>!@#';
  private \Carrot\FilesystemHelper $filesystem_helper;
  private \Carrot\Settings $settings;


  protected function setUp(): void
  {
    ob_start();
    $this->settings = new \Carrot\Settings('source/settings.yml');
    ob_end_clean();
    $this->filesystem_helper = new \Carrot\FilesystemHelper($this->settings->get_export_dir());
    $this->assertInstanceOf('\Carrot\FilesystemHelper', $this->filesystem_helper);
  }

  public function testCreateDirs(): void
  {
    // Supress output
    ob_start();
    $this->filesystem_helper->create_directories([
      'test-dir-1',
      'test-dir-2',
    ]);
    ob_end_clean();

    $this->assertDirectoryExists('test-dir-1');
    $this->assertDirectoryExists('test-dir-2');

    rmdir('test-dir-1');
    rmdir('test-dir-2');
  }

  public function testCreatePost(): void
  {
    $export_dir = $this->settings->get_export_dir();

    $post_data = [
      'slug' => $this->test_slug,
      'markup' => $this->test_markup,
    ];

    $this->filesystem_helper->create_post($post_data, $export_dir);

    $file_full_path = $export_dir . $this->test_slug . '/index.html';
    $this->assertFileExists($file_full_path);

    $file_contents = file_get_contents($file_full_path);
    $this->assertEquals($this->test_markup, $file_contents);

    unlink($export_dir . $this->test_slug . '/index.html');
    rmdir($export_dir . '/' . $this->test_slug);
  }

  public function testCopyDir(): void
  {
    // Supress output
    ob_start();
    $this->filesystem_helper->create_directories([
      'test-dir-1',
    ]);
    ob_end_clean();

    $this->filesystem_helper->copy_dir('test-dir-1', 'test-dir-3');
    $this->assertDirectoryExists('test-dir-1');
    $this->assertDirectoryExists('test-dir-3');

    rmdir('test-dir-1');
    rmdir('test-dir-3');
  }
}

