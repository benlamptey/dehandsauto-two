<?php

namespace Drupal\auto_be\EventSubscriber;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Drupal\commerce_cart\Event\CartEntityAddEvent;
use Drupal\commerce_cart\Event\CartEvents;
use Drupal\Core\Url;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger;


class CustomSubscriber implements EventSubscriberInterface {

  public function printoninit(GetResponseEvent $event) {
    $route_name = \Drupal::request()->get(RouteObjectInterface::ROUTE_NAME);
    //print_r($route_name);
    $current_path = \Drupal::service('path.current')->getPath();
    $url = explode('/', $current_path);
    //print_r($url);
    //exit();
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();
    if (\Drupal::currentUser()->id() != 1) {

      if(in_array('anonymous', array_values($roles))){

        if ($url[1] == 'user' && isset($url[2]) && $url[2] == 'register') {
          $path = \Drupal\Core\Url::fromRoute('custom.form')->toString();
          $response = new RedirectResponse($path,301);
          $response->send();
        }

      }

      if($route_name == 'entity.user.edit_form'){
          $path = \Drupal\Core\Url::fromRoute('user_profile.form')->toString();
          $response = new RedirectResponse($path,301);
          $response->send();
      }

      /**if($route_name == 'add_veiling_invoer_handelaren.form' || $route_name == 'add_veiling_invoer_particulieren.form'){
          $message = t('Om een voertuig te veilen en of te bieden dient u ingelogt te zijn. Nog geen account, dit is vlug gebeurd: <a href="/user/account/register">registreer nu</a>');
          $messenger = \Drupal::messenger();
          $messenger->addMessage($message, $messenger::TYPE_WARNING, TRUE);
          $path = \Drupal\Core\Url::fromRoute('user.login')->toString();
          $response = new RedirectResponse($path);
          $response->send();
      }*/


    }
    
  }


  /**
     * {@inheritdoc}
     */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('printoninit');
    return $events;
  }
}