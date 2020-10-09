<?php

/**
 * @file
 * Contains \Drupal\edit_profiel_blok\Form\AdditionalDetailsForm.
 */

namespace Drupal\edit_profiel_blok\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\user\Entity\User;

class AdditionalDetailsForm extends ContentEntityForm {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'user_additional_details';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    self::setEntity(User::load($this->currentUser()->id()));

    /* @var $entity \Drupal\user\Entity\User */
    $form = parent::buildForm($form, $form_state);

    $form['field_land']['widget']['0']['value']['#required'] = true;
    $form['field_postcode']['widget']['0']['value']['#required'] = true;
    
    
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    $form_state->setRedirect(
      'entity.user.edit_form',
      ['user' => $this->entity->id()]
    );
  }
}