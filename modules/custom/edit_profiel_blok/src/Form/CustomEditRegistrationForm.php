<?php
/**
 * @file
 * Contains \Drupal\edit_profiel_blok\Form\CustomEditRegistrationForm
 */
namespace Drupal\edit_profiel_blok\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Messenger\MessengerInterface;

class CustomEditRegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_first_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

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


    $form['naam'] = array(
  	  '#type' => 'textfield',
  	  '#placeholder' => t('Gebruikersnaam'),
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

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('maak account aan'),
      '#button_type' => 'primary',
      '#weight' => 11,
    );

    $form['aleadyaccount'] = array(
        '#type' => 'markup',
        '#markup' => t('<div class="already-account">reeds een account? <a href="/user/login">log in</a></div>'),
        '#weight' => 100,
      );

    $form['description'] = array(
	      '#type' => 'markup',
	      '#markup' => t('<div class="register-desc"><p>Door een account aan te maken word je een klant van 2dehandsauto en accepteer je onze
Gebruiksvoorwaarden en Privacyverklaring. Je zult systeemberichten gerelateerd aan je advertenties
en berichten van de klantenservice ontvangen.</p></div>'),
	      '#weight' => 200,
	    );

     return $form;
  }


   public function validateForm(array &$form, FormStateInterface $form_state) {
      $pass = $form_state->getValue('pass');
      $confirm_pass = $form_state->getValue('confirm_pass');
      $user_mail = $form_state->getValue('user_mail');
      $nom = $form_state->getValue('naam');

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
      $nom = $form_state->getValue('naam');
      $user_mail = $form_state->getValue('user_mail');
      $pass = $form_state->getValue('pass');
      $account_type = $form_state->getValue('account_type');

      $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $user = \Drupal\user\Entity\User::create();
      $user->setPassword($pass);
      $user->enforceIsNew();
      $user->setEmail($user_mail);
      $user->setUsername(strtolower($nom));

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
        \Drupal::messenger()->addMessage($message, 'error');

        \Drupal::logger('mail-log')->error($message);
        return;
      }else{
        $message = t('Uw account is aangemaakt. Controleer uw email om in te loggen');
        \Drupal::messenger()->addMessage($message);
        \Drupal::logger('mail-log')->notice($message);
      }
    }


   }
}
