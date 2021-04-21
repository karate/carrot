<?php
namespace Carrot;

use Symfony\Component\Yaml\Yaml;

class Settings {
  private array $settings;
  private $source_dir = 'source/posts/';
  private $export_dir = 'publish/';

  /**
   * Parse the app/settings.yml file
   */
  public function __construct($settings_file_path) {
    echo "Reading settings...\n";
    $settings = Yaml::parse(file_get_contents($settings_file_path));

    // Add trailing slash
    foreach ($settings['paths'] as &$path) {
      $path = rtrim($path, '/') . '/';
    }

    $this->settings = $settings;
  }

  /**
   * Getters FTW!
   */
  public function get_all(): array {
    return $this->settings;
  }

  public function get_site_name(): string {
    return $this->settings['site']['name'];
  }

  public function get_site_description(): string {
    return $this->settings['site']['description'];
  }

  public function get_web_dir(): string {
    return $this->settings['paths']['web_dir'];
  }

  public function get_menu_max_items(): int
  {
    return $this->settings['menu']['max_items'];
  }

  public function get_menu_show_home(): bool
  {
    return $this->settings['menu']['show_home'];
  }

  public function get_export_dir(): string
  {
    return $this->export_dir;
  }

  public function get_source_dir(): string
  {
    return $this->source_dir;
  }
}
