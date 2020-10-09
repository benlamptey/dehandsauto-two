<?php
namespace Drupal\auto_be\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Drupal\Core\Form\FormInterface;
//use \Drupal\user\Entity\User;
//use Drupal\node\Entity\Node;
//use Drupal\Core\Url;
//use Drupal\Core\Link;
//use Drupal\Core\Entity\EntityInterface;

/**
 * Provides route responses for the Example module.
 */
class CustomController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */ 
  public function add_veiling_invoer_handelaren() {
    
    $element = array(
      '#markup' => t(''),
      '#cache' => array(
        'max-age' => 0,
      ),
    );
    return $element;
  }

  public function add_veiling_invoer_particulieren() {
    
    $element = array(
      '#markup' => t(''),
      '#cache' => array(
        'max-age' => 0,
      ),
    );
    return $element;
  }
}