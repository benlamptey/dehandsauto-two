<?php

namespace Drupal\biedconditie\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'My Template' block.
 *
 * @Block(
 *   id = "custom_exposed_form_block",
 *   admin_label = @Translation("Custon exposed form template")
 * )
 */
class customExposedFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $renderable = [
      '#theme' => 'custom-exposed-form',
      'render element' => 'form',
    ];

    return $renderable;
  }

}