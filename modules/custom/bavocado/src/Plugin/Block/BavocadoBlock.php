<?php

namespace Drupal\bavocado\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'BavocadoBlock' block.
 *
 * @Block(
 *  id = "bavocado_block",
 *  admin_label = @Translation("Inschrijving nieuwsbrief"),
 * )
 */
class BavocadoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\bavocado\Form\BavocadoForm');

    return $form;
  }

}
