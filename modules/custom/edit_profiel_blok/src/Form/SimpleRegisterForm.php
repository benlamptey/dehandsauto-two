<?php
/**
 * @file
 * Contains \Drupal\edit_profiel_blok\Form\SimpleRegisterForm.
 */

namespace Drupal\edit_profiel_blok\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RegisterForm;

/**
 * Form handler for the user register forms.
 */
class SimpleRegisterForm extends RegisterForm {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'simple_registration';
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $account = $this->entity;
    $pass = $account->getPassword();

    // Save has no return value so this cannot be tested.
    // Assume save has gone through correctly.
    $account->save();

    $form_state->set('user', $account);
    $form_state->setValue('uid', $account->id());

    $this->logger('user')->notice('New user: %name %email.', array('%name' => $form_state->getValue('name'), '%email' => '<' . $form_state->getValue('mail') . '>', 'type' => $account->EntityInterface::toLink($this->t('Edit'), 'edit-form')));

    // Add plain text password into user account to generate mail tokens.
    $account->password = $pass;

    _user_mail_notify('register_no_approval_required', $account);
    user_login_finalize($account);
    $form_state->setRedirect('edit_profiel_blok.additional_details');
  }
}