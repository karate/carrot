<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers \Carrot\Post
 * @uses \Carrot\Settings
 */
final class PostTest extends TestCase
{
  private \Carrot\Settings $settings;
  private \Carrot\Post $post_with_menu;
  private \Carrot\Post $post_without_menu;

  private string $title  = 'Hello World';
  private string $date   = '2021-03-17';
  private string $slug_with_menu   = 'hello-world-with-menu';
  private string $slug_without_menu   = 'hello-world-without-menu';
  private string $markup = 'test markup';
  private string $menu   = 'Hello';

  private function createPostWithMenu(): void
  {
    $post_content = <<<EOT
---
title: '$this->title'
date: '$this->date'
slug: '$this->slug_with_menu'
menu: '$this->menu'
---
$this->markup
EOT;

    $post_full_parh = $this->settings->get_source_dir() . 'test-post-with-menu.md';
    file_put_contents($post_full_parh, $post_content);
    $this->post_with_menu = new \Carrot\Post($post_full_parh);
    $this->assertInstanceOf('\Carrot\Post', $this->post_with_menu);
  }

  private function createPostWithoutMenu(): void
  {
    $post_content = <<<EOT
---
title: '$this->title'
date: '$this->date'
slug: '$this->slug_without_menu'
---
$this->markup
EOT;

    $post_full_parh = $this->settings->get_source_dir() . 'test-post-without-menu.md';
    file_put_contents($post_full_parh, $post_content);
    $this->post_without_menu = new \Carrot\Post($post_full_parh);
    $this->assertInstanceOf('\Carrot\Post', $this->post_without_menu);
  }

  public function setUp(): void
  {
    ob_start();
    $this->settings = new \Carrot\Settings('source/settings.yml');
    ob_end_clean();
    $this->createPostWithMenu();
    $this->createPostWithoutMenu();
  }

  public function tearDown(): void
  {
    unlink($this->settings->get_source_dir() . 'test-post-with-menu.md');
    unlink($this->settings->get_source_dir() . 'test-post-without-menu.md');
  }

  public function testGetPostData(): void
  {
    // Post with menu
    $post_data = $this->post_with_menu->get_post_data();
    $datetime = DateTime::createFromFormat('Y-m-d', $this->date);
    $this->assertEquals($post_data['date'], $datetime);
    $this->assertEquals($post_data['title'], $this->title);
    $this->assertEquals($post_data['slug'], $this->slug_with_menu);
    $this->assertTrue($post_data['show_in_menu']);
    $this->assertEquals($post_data['menu_title'], $this->menu);
    $expected_markup = "<p>$this->markup</p>";
    $this->assertEquals($post_data['markup'], $expected_markup);

    // Post without menu
    $post_data = $this->post_without_menu->get_post_data();
    $datetime = DateTime::createFromFormat('Y-m-d', $this->date);
    $this->assertEquals($post_data['date'], $datetime);
    $this->assertEquals($post_data['title'], $this->title);
    $this->assertEquals($post_data['slug'], $this->slug_without_menu);
    $this->assertNull($post_data['menu_title']);
    $this->assertFalse($post_data['show_in_menu']);
    $expected_markup = "<p>$this->markup</p>";
    $this->assertEquals($post_data['markup'], $expected_markup);
  }

  public function testGetIsMenu(): void
  {
    $this->assertTrue($this->post_with_menu->is_menu(), 'Test post is not menu');
    $this->assertFalse($this->post_without_menu->is_menu(), 'Test post is menu');
  }

  public function testGetMenuName(): void
  {
    $this->assertEquals($this->post_with_menu->get_menu_name(), $this->menu, 'Test post menu name is wrong');
    $this->assertNull($this->post_without_menu->get_menu_name(), $this->menu, 'Test post should not have a name');
  }
}

