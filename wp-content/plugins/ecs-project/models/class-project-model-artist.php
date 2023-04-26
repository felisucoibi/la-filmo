<?php

class Project_Model_Artist
{

  private $post_type_name;
  private $post_type_singular;
  private $post_type_plural;
  public $template_parser;
  private $menu_icon;
  private $request_type_taxonomy_artist_type;
  private $request_type_taxonomy_artist_type_singular;
  private $request_type_taxonomy_artist_type_plural;

  function __construct($template_parser)
  {
    $this->template_parser = $template_parser;
    $this->post_type_name = 'artista';
    $this->post_type_singular = __('Artista', 'enigmind');
    $this->post_type_plural = __('Artistas', 'enigmind');
    $this->menu_icon = 'dashicons-admin-users';

    $this->request_type_taxonomy_artist_type = 'artist_type';
    $this->request_type_taxonomy_artist_type_singular = __('Categoría', 'enigmind');
    $this->request_type_taxonomy_artist_type_plural = __('Categorías', 'enigmind');

    add_action('init', array($this, 'create_post_type'));
    add_action('init', array($this, 'create_taxonomies'));
    add_action('cmb2_admin_init', array($this, 'add_meta_boxes'));

    add_action('wp_enqueue_scripts', array($this, 'load_frontend_scripts'));
    add_action('wp_enqueue_scripts', array($this, 'load_frontend_styles'));

    add_action('admin_print_styles-post.php', array($this, 'load_admin_styles'), 1000);
    add_action('admin_print_styles-post-new.php', array($this, 'load_admin_styles'), 1000);

    add_action('admin_print_scripts-post.php', array($this, 'load_admin_scripts'), 1000);
    add_action('admin_print_scripts-post-new.php', array($this, 'load_admin_scripts'), 1000);
  }

  function create_post_type()
  {

    $labels = array(
      'name' => sprintf(_x('%s', 'post type general name', 'enigmind'), $this->post_type_plural),
      'singular_name' => sprintf(_x('%s', 'post type singular name', 'enigmind'), $this->post_type_singular),
      'add_new' => _x('Agregar Nueva', $this->post_type_singular, 'enigmind'),
      'add_new_item' => sprintf(__('Nuevo %s', 'enigmind'), $this->post_type_singular),
      'edit_item' => sprintf(__('Editar %s', 'enigmind'), $this->post_type_singular),
      'new_item' => sprintf(__('Agregar %s', 'enigmind'), $this->post_type_singular),
      'all_items' => sprintf(__('%s', 'enigmind'), $this->post_type_plural),
      'view_item' => sprintf(__('Ver %s', 'enigmind'), $this->post_type_singular),
      'search_items' => sprintf(__('Buscar', 'enigmind'), $this->post_type_plural),
      'not_found' => sprintf(__('No %s Encontrados', 'enigmind'), $this->post_type_plural),
      'not_found_in_trash' => sprintf(__('No %s Encontrados en la Papelera', 'enigmind'), $this->post_type_plural),
      'parent_item_colon' => '',
      'menu_name' => $this->post_type_plural,
    );

    $args = array(
      'labels' => $labels,
      'description'         => __('Artista', 'enigmind'),
      'supports'            => array('title', 'editor', 'thumbnail'),
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 4,
      'menu_icon'           =>  $this->menu_icon,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
    );

    register_post_type($this->post_type_name, $args);
  }


  function artist_type_taxonomy()
  {
    $labels = array(
      'name'              => $this->request_type_taxonomy_artist_type_singular,
      'singular_name'     => $this->request_type_taxonomy_artist_type_singular,
      'search_items'      => sprintf(__('Buscar %s', 'enigmind'), $this->request_type_taxonomy_artist_type_plural),
      'all_items'         => sprintf(__('Todos los %s', 'enigmind'), $this->request_type_taxonomy_artist_type_plural),
      'parent_item'       => __('Parent Genre', 'textdomain'),
      'parent_item_colon' => __('Parent Genre:', 'textdomain'),
      'edit_item'         => sprintf(__('Editar %s', 'enigmind'), $this->request_type_taxonomy_artist_type_singular),
      'update_item'       => sprintf(__('Actualizar %s', 'enigmind'), $this->request_type_taxonomy_artist_type_singular),
      'add_new_item'      => sprintf(__('Agregar nuevo %s', 'enigmind'), $this->request_type_taxonomy_artist_type_singular),
      'new_item_name'     => sprintf(__('Nuevo %s', 'enigmind'), $this->request_type_taxonomy_artist_type_singular),
      'menu_name'         => $this->request_type_taxonomy_artist_type_plural,
    );

    $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'show_in_menu'          => true,
      'show_in_nav_menus'     => true,
      'query_var'         => true,
      'rewrite'       => array(
        'slug' => $this->request_type_taxonomy_artist_type
      ),
      'hierarchical'  => true
    );

    register_taxonomy($this->request_type_taxonomy_artist_type, array($this->post_type_name), $args);
  }

  function create_taxonomies()
  {
    $this->artist_type_taxonomy();
  }

  function metabox_general()
  {
    $prefix = 'artist_';

    $cmb = new_cmb2_box(array(
      'id'           => $prefix . 'general',
      'title'        => __('Información extra', 'enigmind'),
      'object_types' => array($this->post_type_name,), // Post type
      'context'      => 'normal',
      'priority'     => 'high',
      'show_names'   => true, // Show field names on the left
    ));

    $cmb->add_field(array(
      'name'    => 'Link de Instagram',
      'desc'    => '',
      'id'      => $prefix . 'instagram',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de TikTok',
      'desc'    => '',
      'id'      => $prefix . 'tiktok',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de YouTube',
      'desc'    => '',
      'id'      => $prefix . 'youtube',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de Twitter',
      'desc'    => '',
      'id'      => $prefix . 'twitter',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de Facebook',
      'desc'    => '',
      'id'      => $prefix . 'facebook',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de Artstation',
      'desc'    => '',
      'id'      => $prefix . 'artstation',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de Behance',
      'desc'    => '',
      'id'      => $prefix . 'behance',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'    => 'Link de LinkedIn',
      'desc'    => '',
      'id'      => $prefix . 'linkedin',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name'             => 'Opcion de pago',
      'desc'             => '',
      'id'               => $prefix . 'pay_opcion',
      'type'             => 'select',
      'show_option_none' => true,
      'default'          => 'custom',
      'options'          => array(
        'paypal' => __('PayPal', 'cmb2'),
        'kofi' => __('Ko-fi', 'cmb2'),
        'cafecito' => __('Cafecito', 'cmb2'),
        'matecito' => __('Matecito', 'cmb2'),
      ),
    ));

    $cmb->add_field(array(
      'name'    => 'Link de pago',
      'desc'    => '',
      'id'      => $prefix . 'paypal',
      'type'    => 'text'
    ));

    $cmb->add_field(array(
      'name' => 'Otros trabajos',
      'desc' => 'Agregar imagenes de otros trabajor.',
      'id'   => $prefix . 'others',
      'type' => 'file_list',
    ));

    $cmb->add_field(array(
      'name'    => 'Descripcion de otros trabajos',
      'desc'    => '',
      'id'      => $prefix . 'description_others',
      'type'    => 'textarea'
    ));
  }

  function add_meta_boxes()
  {
    $this->metabox_general();
  }

  function load_admin_styles()
  {
    global $post_type;

    if ($this->post_type_name != $post_type) {
      return;
    }
  }

  function load_frontend_styles()
  {

    global $post_type;

    if ($this->post_type_name != $post_type) {
      return;
    }
  }

  function load_admin_scripts()
  {
    global $post_type;

    if ($this->post_type_name != $post_type) {
      return;
    }
  }

  function load_frontend_scripts()
  {

    global $post_type;

    if ($this->post_type_name != $post_type) {
      return;
    }
  }
}
