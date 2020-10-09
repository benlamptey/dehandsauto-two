<?php

namespace Drupal\edit_profiel_blok\Plugin\Block;

    use Drupal\Core\Block\BlockBase;
        
    /**
 * Provides a block for additional editing the user form.
 *
 * @Block(
 *   id = "blok_account_verwijderen",
 *   admin_label = @Translation("Additional account information form"),
 *   provider = "user",
 *   category = @Translation("Forms")
 * )
 *
 */
class VerwijderAccountBlock extends BlockBase{

  
/**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\edit_profiel_blok\Form\VerwijderAccountForm');
    return $form;
    }
}
