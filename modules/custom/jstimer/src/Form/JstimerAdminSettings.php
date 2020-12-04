<?php

/**
 * @file
 * Contains \Drupal\jstimer\Form\JstimerAdminSettings.
 */

namespace Drupal\jstimer\Form;

use Drupal\Core\Asset\JsCollectionOptimizer;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JstimerAdminSettings extends ConfigFormBase {

  /**
   * Used to clear the JS caches.
   *
   * @var \Drupal\Core\Asset\JsCollectionOptimizer
   */
  protected $js_collection_optimizer;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    JsCollectionOptimizer $jsCollectionOptimizer
  ) {
    parent::__construct($config_factory);
    $this->js_collection_optimizer = $jsCollectionOptimizer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('asset.js.collection_optimizer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jstimer_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['jstimer.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('jstimer.settings');

    $form = [];

	// Placeholder method for global jstimer settings.  Currently none.

    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('jstimer.settings');

    $config->save();
    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }
    parent::submitForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      if (substr($key, 0, 11) == "jstimer_js_") {
        if (!strpos($value, "'") === FALSE) {
          $form_state->setErrorByName($key, $this->t("Javascript Timer admin settings may not contain single quotes(')."));
        }
      }
    }
  }

 /**
  * Save admin settings, write jstimer.js file, and clear js cache (for aggregation).
  */
  public function _submitForm(array &$form, FormStateInterface $form_state) {
    // build timer.js file with new settings.
    jstimer_build_js_cache();
    $this->js_collection_optimizer->deleteAll();
  }
}
