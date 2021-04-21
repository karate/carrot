<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers \Carrot\Reader
 * @uses \Carrot\Settings
 */
final class ReaderTest extends TestCase
{
  private \Carrot\Settings $settings;
  private \Carrot\Reader $reader;

  public function setUp(): void
  {
    ob_start();
    $this->settings = new \Carrot\Settings('source/settings.yml');
    ob_end_clean();

    fopen($this->settings->get_source_dir() . 'test-1.md', 'w');
    fopen($this->settings->get_source_dir() . 'test-2.md', 'w');
    fopen($this->settings->get_source_dir() . '.hidden-test.md', 'w');

    // Supress output
    ob_start();
    $this->reader = new \Carrot\Reader($this->settings->get_source_dir());
    ob_end_clean();
  }

  public function tearDown(): void
  {
    unlink($this->settings->get_source_dir() . 'test-1.md');
    unlink($this->settings->get_source_dir() . 'test-2.md');
    unlink($this->settings->get_source_dir() . '.hidden-test.md');
  }

  public function testReadPostFileNames(): void
  {
    // Supress output
    $posts = $this->reader->get_post_filenames();
    $this->assertContains('test-1.md', $posts);
    $this->assertContains('test-2.md', $posts);
  }

  public function testReadHiddenFileNames(): void
  {
    // Supress output
    ob_start();
    $posts = $this->reader->get_post_filenames();
    ob_end_clean();
    $this->assertNotContains('.hidden-test.md', $posts);
  }
}

