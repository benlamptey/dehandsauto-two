<?php

/**
 * @file
 * Contains \Drupal\biedconditie\Controller\biedconditieController.
 */
 
namespace Drupal\biedconditie\Controller;
 
use Drupal\Core\Controller\ControllerBase;
 
class biedconditieController extends ControllerBase {
  public function content() {
 
      return[
        theme =>'custom-exposed-form', 
                'render element' => 'form',
      ];
  }
}