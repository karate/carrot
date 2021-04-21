<?php
namespace Carrot;

/**
 * @codeCoverageIgnore
 */
class App {
  private \Carrot\Settings $settings;

  private array $menu = [];
  private array $index = [];
  private array $posts = [];

  private \Twig\TemplateWrapper $twig_template;

  public function __construct()
  {
    // Initialize all objects
    $this->settings = new \Carrot\Settings('source/settings.yml');
    $this->initialize_settings();

    $this->filesystem_helper = new \Carrot\FilesystemHelper($this->export_dir);
    $this->reader = new \Carrot\Reader($this->source_dir);
  }

  private function initialize_settings(): void
  {
    // Read all settigns and store them in private variables
    $this->site_name = $this->settings->get_site_name();
    $this->site_description = $this->settings->get_site_description();

    $this->web_dir = $this->settings->get_web_dir();

    $this->menu_show_home = $this->settings->get_menu_show_home();
    $this->menu_max_items = $this->settings->get_menu_max_items();

    $this->export_dir = $this->settings->get_export_dir();
    $this->source_dir = $this->settings->get_source_dir();
  }

  public function run(): void
  {
    $this->create_directories();
    $this->read_posts();
    $this->finalize_menu();
    $this->load_twig_templates();
    $this->create_pages();
    $this->create_index();
  }

  private function load_twig_templates(): void
  {
    // Load twig templates
    $loader = new \Twig\Loader\FilesystemLoader('source/templates');
    $twig = new \Twig\Environment($loader);
    $this->twig_template = $twig->load('page.html.twig');
  }

  private function create_directories(): void
  {
    // Create export dir
    $this->filesystem_helper->create_directories([
      $this->export_dir,
      $this->export_dir . 'resources',
    ]);

    $this->filesystem_helper->copy_dir('resources', $this->export_dir . 'resources');
  }

  private function read_posts(): void
  {
    foreach($this->reader->get_post_filenames() as $post_filename) {
      $post = new \Carrot\Post($this->source_dir . $post_filename);
      $post_data = $post->get_post_data();

      // Menu items
      if ($post->is_menu()) {
        print('* ' . $post_data['menu_title'] . "\n");
        $this->add_menu_item(
          $post_data['menu_title'],
          $this->settings->get_web_dir() . $post_data['slug'],
        );
      }
      // Blog posts
      else {
        echo "- " . $post_data['title'] . "\n";
        // Add to index page
        $this->index[] = [
          'title'   => $post_data['title'],
          'slug'    => $post_data['slug'],
          'date'    => $post_data['date'],
        ];
      }

      $blog = [
        'title'   => $post_data['title'],
        'slug'    => $post_data['slug'],
        'date'    => $post_data['date'],
        'is_menu' => $post_data['show_in_menu'],
        'content' => $post_data['markup'],
      ];

      $this->posts[] = $blog;
    }
  }

  private function add_menu_item($title, $slug, $prepend = false) {
    $menu_data = [
      'title' => $title,
      'slug'  => $slug,
    ];

    if ($prepend) {
      // Prepend to the menu
      array_unshift($this->menu, $menu_data);
    }
    else {
      // Append at the menu
      array_push($this->menu, $menu_data);
    }
  }

  private function finalize_menu(): void
  {
    if ($this->menu_show_home) {
      $this->add_menu_item('Home', $this->web_dir, true);
    }

    if (count($this->menu) > $this->menu_max_items) {
      $this->menu = array_slice($this->menu, 0, $this->menu_max_items);
    }
  }

  private function create_pages(): void
  {
    $site = [
      'name' => $this->settings->get_site_name(),
      'description' => $this->settings->get_site_description(),
      'web_dir' => $this->settings->get_web_dir(),
    ];

    foreach($this->posts as $blog_post) {
      $output = $this->twig_template->render(['is_index' => false, 'site' => $site, 'post' => $blog_post, 'menu' => $this->menu]);
      $this->filesystem_helper->create_post(['slug' => $blog_post['slug'], 'markup' => $output]);
    }
  }

  private function create_index(): void
  {
    $site = [
      'name' => $this->settings->get_site_name(),
      'description' => $this->settings->get_site_description(),
      'web_dir' => $this->settings->get_web_dir(),
    ];

    // Order posts by date to create the index page
    usort($this->index, function($a, $b){
      return ($a['date'] < $b['date']);
    });

    $output = $this->twig_template->render(['is_index' => true, 'site' => $site, 'posts' => $this->index, 'menu' => $this->menu]);
    $this->filesystem_helper->create_post(['slug' => '', 'markup' => $output]);
  }
}
