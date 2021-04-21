<?php

namespace Carrot;

use DateTime;
use Pagerange\Markdown\MetaParsedown;

class Post {
  private string $title;
  private \DateTime $date;
  private string $slug;
  private string $markup;
  private bool $show_in_menu = false;
  private ?string $menu_name;

  public function __construct($file_name_full_path)
  {
    // Get the raw contents of the file
    $file_contents = file_get_contents($file_name_full_path);

    // MetaParsedown
    $mp = new MetaParsedown();
    // Parse markup
    $this->markup = $mp->text($file_contents);

    // Parse metadata
    $meta = $mp->meta($file_contents);

    $this->date  = DateTime::createFromFormat('Y-m-d', $meta['date']);
    $this->title = $meta['title'];
    $this->slug  = $meta['slug'];
    if (isset($meta['menu'])) {
      $this->show_in_menu = true;
      $this->menu_name = $meta['menu'];
    }
    else {
      $this->show_in_menu = false;
      $this->menu_name = null;
    }
  }

  public function get_post_data(): array
  {
    return [
      'title'  => $this->get_title(),
      'date'   => $this->get_date(),
      'slug'   => $this->get_slug(),
      'markup' => $this->get_markup(),
      'show_in_menu' => $this->show_in_menu,
      'menu_title' => $this->menu_name,
    ];
  }

  public function get_date(): \DateTime
  {
    return $this->date;
  }

  public function get_slug(): string
  {
    return $this->slug;
  }

  public function get_markup(): string
  {
    return $this->markup;
  }

  public function get_title(): string
  {
    return $this->title;
  }

  public function is_menu(): bool
  {
    return $this->show_in_menu;
  }

  public function get_menu_name(): ?string
  {
    if ($this->is_menu()) {
      return $this->menu_name;
    }
    return null;
  }
}
