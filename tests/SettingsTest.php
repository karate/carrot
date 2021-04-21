<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers \Carrot\Settings
 */
final class SettingsTest extends TestCase
{

  private $settings;

  /**
   * @before
   */
  public function testSettingsFileExists(): void
  {
    $this->assertFileExists('source/settings.yml');

    // Supress output
    ob_start();
    $this->settings = new \Carrot\Settings('source/settings.yml');
    ob_end_clean();
    $this->assertInstanceOf('Carrot\Settings', $this->settings);
  }

  public function testCanReadAllSettings(): void
  {
    $settings = $this->settings->get_all();
    //  $settings is an array
    $this->assertIsArray($settings);
    // $settings['site'] exists
    $this->assertArrayHasKey(
      'site',
      $settings,
      "Settings array does not contain key 'site'"
    );
    // $settings['paths'] exists
    $this->assertArrayHasKey(
      'paths',
      $settings,
      "Settings array does not contain key 'paths'"
    );
    // $settings['menu'] exists
    $this->assertArrayHasKey(
      'menu',
      $settings,
      "Settings array does not contain key 'menu'"
    );
  }

  public function testCanReadSiteName(): void
  {
    $site_name = $this->settings->get_site_name();
    $this->assertTrue(
      is_string($site_name), "Site name not found"
    );
  }

  public function testCanReadSiteDesrcription(): void
  {
    $site_description = $this->settings->get_site_description();
    $this->assertTrue(
      is_string($site_description), "Site description not found"
    );
  }

  public function testCanReadSiteWebDir(): void
  {
    $web_dir = $this->settings->get_web_dir();
    $this->assertTrue(
      is_string($web_dir), "Site web dir not found"
    );
  }

  public function testCanReadExportDir(): void
  {
    $web_dir = $this->settings->get_export_dir();
    $this->assertTrue(
      is_string($web_dir), "Site export dir not found"
    );
  }

  public function testCanReadSourceDir(): void
  {
    $max_items = $this->settings->get_source_dir();
    $this->assertTrue(
      is_string($max_items), "Site source dir not found"
    );
  }

  public function testCanReadMenuShowHome(): void
  {
    $show_home = $this->settings->get_menu_show_home();
    $this->assertTrue(
      is_bool($show_home), "Menu show home not found"
    );
  }

  public function testCanReadMenuMaxItems(): void
  {
    $show_home = $this->settings->get_menu_max_items();

    $this->assertTrue(
      is_int($show_home), "Menu max items not found or not a number"
    );

    $this->assertTrue(
      $show_home > 0, "Menu max items is not greater than 0"
    );
  }
}
