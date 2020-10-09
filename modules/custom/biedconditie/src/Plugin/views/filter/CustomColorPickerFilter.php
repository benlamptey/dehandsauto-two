<?php

namespace Drupal\biedconditie\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\ManyToOne;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;


/**
 * Filters by kleur.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("custom_color_picker_filter")
 */
class CustomColorPickerFilter extends ManyToOne {

  /**
   * The current display.
   *
   * @var string
   *   The current display of the view.
   */
  protected $currentDisplay;

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->definition['options callback'] = [$this, 'generateOptions'];
    $this->currentDisplay = $view->current_display;
  }

  /**
   * Helper function that generates the options.
   *
   * @return array
   *   An array of colors
   */
  public function generateOptions() {
    $field_name = 'field_kleur_picker';
    $entity_type = 'node';
    $bundle = 'veiling_invoer_handelaren';
    $settings = \Drupal::service('entity_type.manager')
      ->getStorage('entity_form_display')
      ->load($entity_type . '.' . $bundle . '.default')
      ->getRenderer($field_name)
      ->getSettings();
    if (isset($settings['default_colors'])) {
      $explode_colors = explode(',', $settings['default_colors']);
      $trimmed_colors = [];
      foreach ($explode_colors as $color) {
        $trimmed_colors[] = ltrim($color, '#');
      }
      // Use values as keys.
      return array_combine($trimmed_colors, $trimmed_colors);
    }
  }

  /**
   * Helper function that builds the query.
   */
  public function query() {
    if (!empty($this->value)) {
      $configuration = [
        'table' => 'node__field_kleur_picker',
        'field' => 'entity_id',
        'left_table' => 'node_field_data',
        'left_field' => 'nid',
        'operator' => '=',
      ];
      $join = Views::pluginManager('join')->createInstance('standard', $configuration);
      $this->query->addRelationship('node__field_kleur_picker', $join, 'node_field_data');
      $this->query->addWhere('AND', 'node__field_kleur_picker.field_kleur_picker_color', $this->value, 'IN');
    }
  }
}
