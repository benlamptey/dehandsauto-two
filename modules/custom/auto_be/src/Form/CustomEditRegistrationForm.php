<?php
/**
 * @file
 * Contains \Drupal\auto_be\Form\CustomEditRegistrationForm
 */
namespace Drupal\auto_be\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
class CustomEditRegistrationForm extends FormBase {
    
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_edit_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['new_photo'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Upload een nieuwe foto'),
    ];

    $form['new_photo']['image_file'] = [
      '#title' => t('Upload een nieuwe account foto'),
      '#title_display' => 'invisible',
      '#type' => 'managed_file',
      '#upload_location' => 'public://users/' . date('Y-m'),
      '#multiple' => FALSE,
      '#description' => t('Aanvaarde extensies: <em>gif, png, jpg, jpeg</em>'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_validators' => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [10485760],
      ],
    ];

    $form['account_type'] = array (
      '#type' => 'radios',
      '#title' => ('Kies een account type'),
      '#options' => array(
        'handelaar' => t('Handelaar'),
        'particulier' => t('Particulier'),
      ),
      '#required' => TRUE,
      '#weight' => 1,
    );

    $form['ondernemingsnummer'] = [
      '#type' => 'textfield',
      '#size' => '40',
      '#placeholder' => 'Ondernemingsnummer',
      '#weight' => 2,
      '#attributes' => [
        'id' => 'ondernemingsnummer',
      ],
      '#states' => [
        //show this textfield only if the radio 'handelaar' is selected above
        'visible' => [
          ':input[name="account_type"]' => ['value' => 'handelaar'],
        ],
      ],
    ];


    $form['nom'] = array(
  	  '#type' => 'textfield',
  	  '#placeholder' => t('Naam en voornaam'),
  	  '#required' => TRUE,
  	  '#weight' => 3,
  	);

  	$form['user_mail'] = array(
        '#type' => 'email',
  	  '#placeholder' => t('E-mailadres'),
        '#required' => TRUE,
        '#weight' => 4,
      );


  	$form['pass'] = array(
  	  '#placeholder' => t('Wachtwoord (minimaal 8 tekens)'),
  	  '#type' => 'password',
  	  '#required' => TRUE,
  	  '#weight' => 6,
  	);

  	$form['confirm_pass'] = array(
  	  '#placeholder' => t('Herhaal wachtwoord'),
  	  '#type' => 'password',
  	  '#required' => TRUE,
  	  '#weight' => 7,
  	);

    $form['field_plaats'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Plaats'),
        '#weight' => 8,
      );

    $form['field_land'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Land'),
        '#weight' => 9,
      );

    $form['field_postcode'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Postcode'),
        '#weight' => 10,
      );

    $form['field_telefoonnummer'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Telefoonnummer'),
        '#weight' => 11,
      );
    

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('maak account aan'),
      '#button_type' => 'primary',
      '#weight' => 11,
    );
      
     return $form;
  }


   public function validateForm(array &$form, FormStateInterface $form_state) {
      $pass = $form_state->getValue('pass');
      $confirm_pass = $form_state->getValue('confirm_pass');
      $user_mail = $form_state->getValue('user_mail');
      $nom = $form_state->getValue('nom');

      if($pass != $confirm_pass){
          $form_state->setErrorByName('confirm_pass', t('De beide wachtwoorden moeten hetzelfde zijn.'));
      }

      $user = \Drupal::entityQuery('user')
          ->condition('name', strtolower($nom))
          ->range(0, 1)
          ->execute();
        if(!empty($user)){
          $form_state->setErrorByName('username', t('De gebruikersnaam '.$nom.' is reeds in gebruik.'));
        }

      $email = \Drupal::entityQuery('user')
          ->condition('mail', $user_mail)
          ->range(0, 1)
          ->execute();

          if(!empty($email)){
          $form_state->setErrorByName('email', t('This email '.$user_mail.' already taken.'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {


      $ondernemingsnummer = $form_state->getValue('ondernemingsnummer');
      $nom = $form_state->getValue('nom');
      $user_mail = $form_state->getValue('user_mail');
      $pass = $form_state->getValue('pass');
      $account_type = $form_state->getValue('account_type');
      $field_plaats = $form_state->getValue('field_plaats');
      $field_land = $form_state->getValue('field_land');
      $field_postcode = $form_state->getValue('field_postcode');
      $field_telefoonnummer = $form_state->getValue('field_telefoonnummer');


      $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $user = \Drupal\user\Entity\User::create();
      $user->setPassword($pass);
      $user->enforceIsNew();
      $user->setEmail($user_mail);
      $user->setUsername(strtolower($nom));

      $user->set("field_plaats", $field_plaats);
      $user->set("field_land", $field_land);
      $user->set("field_postcode", $field_postcode);
      $user->set("field_telefoonnummer", $field_telefoonnummer);
      $user->set("field_ondernemingsnummer", $ondernemingsnummer);

      $user->addRole($account_type);


      // Optional.
      $user->set('init', $user_mail);
      $user->set('langcode', $language);
      $user->set('preferred_langcode', $language);
      $user->set('preferred_admin_langcode', $language);
      $user->activate();


      // Save user account.
      $result = $user->save();

      if($result){
      // send email
      $to = $user_mail; // comment when site ready

      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'custom';
      $key = 'registration_mail'; // Replace with Your key
      $body = '<div class="mail-body">
                <p>Uw account werd succesvol aangemaakt.</p>
              </div>';
      $params['subject'] = 'Account aangemaakt';
      $params['message'] = $body;
      $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $send = true;

      $mailSent = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      if ($mailSent['result'] != true) {
        $message = t('De account bevestiging kon niet worden gemaild. Mogelijks is het account toch aangemaakt.
                     Gelieve in dit geval uw mail te controleren. Anders, maak opnieuw een account aan.'); 
        MessengerInterface::addMessage($message, 'error');

        \Drupal::logger('mail-log')->error($message);
        return;
      }else{
        $message = t('Uw account is aangemaakt. Controleer uw email om in te loggen');
        MessengerInterface::addMessage($message);
        \Drupal::logger('mail-log')->notice($message);
      }       
    }



   }
}
