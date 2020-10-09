<?php
/**
 * @file
 * Contains \Drupal\auto_be\Form\UserProfileForm
 */
namespace Drupal\auto_be\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
class UserProfileForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_profile_form';
  }

   /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

  	// Load the current user.
	  $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $user_name = $user->get('name')->value;

	// retrieve field data from that user
  	$email = $user->get('mail')->value;
  	$pass = $user->get('pass')->value;

    $field_plaats = $user->get('field_plaats')->value;
    $field_land = $user->get('field_land')->value;
    $field_postcode = $user->get('field_postcode')->value;
    $field_telefoonnummer = $user->get('field_telefoonnummer')->value;


    $form['nom'] = array(
  	  '#type' => 'textfield',
  	  '#title' => t('Naam en voornaam'),
  	  '#required' => TRUE,
  	  '#weight' => 2,
  	  '#default_value' => $user_name,
  	);

  	$form['user_mail'] = array(
        '#type' => 'email',
  	    '#title' => t('E-mailadres'),
        '#required' => TRUE,
        '#weight' => 4,
        '#default_value' => $email,
      );

  	$form['pass'] = array(
  	  '#title' => t('Wachtwoord (minimaal 8 tekens)'),
  	  '#type' => 'password',
  	  '#required' => FALSE,
  	  '#weight' => 6,
  	  '#default_value' => $pass,
  	);

  	$form['confirm_pass'] = array(
  	  '#title' => t('Herhaal wachtwoord'),
  	  '#type' => 'password',
  	  '#required' => FALSE,
  	  '#weight' => 7,
  	  '#default_value' => $pass,
  	);

    $form['field_plaats'] = array(
        '#type' => 'textfield',
        '#title' => t('Plaats'),
        '#default_value' => $field_plaats,
        '#weight' => 8,
      );

    $form['field_land'] = array(
        '#type' => 'textfield',
        '#default_value' => $field_land,
        '#title' => t('Land'),
        '#weight' => 9,
      );

    $form['field_postcode'] = array(
        '#type' => 'textfield',
        '#title' => t('Postcode'),
        '#default_value' => $field_postcode,
        '#weight' => 10,
      );

    $form['field_telefoonnummer'] = array(
        '#type' => 'textfield',
        '#title' => t('Telefoonnummer'),
        '#default_value' => $field_telefoonnummer,
        '#weight' => 11,
      );
    
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Enregistrer'),
      '#button_type' => 'primary',
    );


    return $form;
  }

    public function validateForm(array &$form, FormStateInterface $form_state) {

		$curr_user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
		$current_user_name = $curr_user->get('name')->value;
		$current_user_email = $curr_user->get('mail')->value;
    $nom = $form_state->getValue('nom');
    $user_mail = $form_state->getValue('user_mail');
    $pass = $form_state->getValue('pass');
    $confirm_pass = $form_state->getValue('confirm_pass');

  	if($pass != $confirm_pass){
      $form_state->setErrorByName('confirm_pass', t('password and confirm password should be match.'));
  	}

   	if($current_user_name != strtolower($nom)){
		$user = \Drupal::entityQuery('user')
        ->condition('name', $nom)
        ->range(0, 1)
        ->execute();
    	if(!empty($user)){
    		$form_state->setErrorByName('nom', t('The username '.$nom.' is already taken.'));
    	}
    }

    if($current_user_email != $user_mail){

    $email = \Drupal::entityQuery('user')
        ->condition('mail', $user_mail)
        ->range(0, 1)
        ->execute();

        if(!empty($email)){
    		$form_state->setErrorByName('email', t('This email '.$user_mail.' already taken.'));
    	}
    }
	        
    }



  public function submitForm(array &$form, FormStateInterface $form_state) {

        $user_mail = $form_state->getValue('user_mail');
      	$pass = $form_state->getValue('pass');
        $nom = $form_state->getValue('nom');

        $field_plaats = $form_state->getValue('field_plaats');
        $field_land = $form_state->getValue('field_land');
        $field_postcode = $form_state->getValue('field_postcode');
        $field_telefoonnummer = $form_state->getValue('field_telefoonnummer');



	  	$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
     	if($user){
		  	$user->setPassword($pass);
			  $user->setEmail($user_mail);
			  $user->setUsername(strtolower($nom));

        $user->set("field_plaats", $field_plaats);
        $user->set("field_land", $field_land);
        $user->set("field_postcode", $field_postcode);
        $user->set("field_telefoonnummer", $field_telefoonnummer);

			  $result = $user->save();
			  if ($result) {
	            MessengerInterface::addMessage('Je profiel werd aangepast');
	        } else {
	            MessengerInterface::addMessage('Fout tijdens de aanpassing', 'fout');
	        }

		}

   }
}