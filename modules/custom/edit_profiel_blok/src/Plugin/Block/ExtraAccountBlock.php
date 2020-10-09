<?php

    namespace Drupal\edit_profiel_blok\Plugin\Block;

    use Drupal\Core\Block\BlockBase;
    use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
    use Drupal\Core\Entity\EntityFormBuilderInterface;
    use Drupal\Core\Session\AccountInterface;
    use Drupal\Core\Entity\EntityStorageInterface;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    
/**
 * Provides a block for additional editing the user form.
 *
 * @Block(
 *   id = "block_extra_account",
 *   admin_label = @Translation("Additional account information form"),
 *   provider = "user",
 *   category = @Translation("Forms")
 * )
 *
 */
class ExtraAccountBlock extends BlockBase implements ContainerFactoryPluginInterface {

     /**
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface
   */
  protected $entityFormBuilder;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityFormBuilderInterface $entity_form_builder, AccountInterface $current_user, EntityStorageInterface $user_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityFormBuilder = $entity_form_builder;
    $this->currentUser = $current_user;
    $this->userStorage = $user_storage;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.form_builder'),
      $container->get('current_user'),
      $container->get('entity_type.manager')->getStorage('user', 'additional')
    );
  }
  
  public function build() {
    $user = $this->userStorage->load($this->currentUser->id());
    return $this->entityFormBuilder->getForm($user, 'additional');
    
  }
}