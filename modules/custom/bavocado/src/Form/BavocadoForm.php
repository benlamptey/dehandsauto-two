<?php

namespace Drupal\bavocado\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BavocadoForm.
 */
class BavocadoForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bavocado_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#size' => 23,
      '#placeholder' => $this ->t('Vul hier je email in'),
      '#weight' => '0'
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Ingeven')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

    	
  	/**
   * {@inheritdoc}
   */
   
 public function submitForm(array &$form, FormStateInterface $form_state){
   
     $email = $form_state->getValue('email');
     
     $langcode = 'nl';
	 $to = 'belbabel@gmail.com';
	 $mailManager = \Drupal::service('plugin.manager.mail');
     $module = 'bavocado';
     $key = 'Nieuwe inschrijving nieuwsbrief';
     $params = array(
	 'subject' => 'Inschrijving nieuwsbrief 2deHandsAuto',
     'body' => "<p>Dag Wim,</p><br><p>Een nieuwe inschrijving voor de nieuwsbrief van 2deHandsAuto.</p><p>Gelieve dit email adres op te slaan : $email</p><br><p>Deze mail, nog de inschrijver van de nieuwsbrief niet beantwoorden. Deze info is enkel voor administratieve doeleinden.</p>"
      );
	 $send = true;
     $result = $mailManager->mail( $module, $key, $to, $langcode, $params, NULL, $send); 
     
    if ($result['result'] !== true) {
   MessengerInterface::addMessage($this->t( '<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"><div class="w3-container w3-section
	w3-red w3-bottombar w3-border-brown w3-border w3-animate-opacity">
	<h4><b>Uw email werd niet geregistreerd. Fout!</b></h4></div>	
 '));
 }
    else {
   MessengerInterface::addMessage($this->t( '<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"><div class="w3-container w3-section
	w3-pale-green w3-bottombar w3-border-green w3-border w3-animate-opacity">
	<h4 class="w3-text-gray">Uw inschrijving was succesvol.</h4></div>	
 '));
 }	  
  }

}
