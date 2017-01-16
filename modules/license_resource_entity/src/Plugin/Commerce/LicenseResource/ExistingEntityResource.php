<?php
namespace Drupal\license_resource_entity\Plugin\Commerce\LicenseResource;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
* @file
* - Provides a user access to an existing entity.
*
* @CommerceLicenseResource(
*   id = "resource_existing_entity",
*   label = "Exsiting Entity Access",
*   display_label = "Existing Entity Access"
* )
*
*/
class ExistingEntityResource extends ConditionPluginBase {

  public function summary() {
    return t('Select a entity type, entity, and action to provide access for.');
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    parent::buildConfigurationForm($form, $form_state);

    // Maybe better to have autocomplete here?
    $form['entity'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#title' => t('Entity'),
      '#description' => t('Select the entity you wish to provide access for.')
    ];


    return $form;
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {

  }

  public function entityTypeSelectAjax(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(true);

    $form['bundle']['#options'] = $this->getEntityBundles( $form_state->getValue('entity_type') );
    return $form['bundle'];
  }

  public function evaluate() {
    drupal_set_message('evaluate');
    return true;
  }

  private function getEntityTypes() {
    $options = [
      -1 => t('Select an Entity type')
    ];

    $definitions = \Drupal::entityManager()->getDefinitions();

    foreach($definitions as $entity_type => $definition) {
      if (!is_a($definition, 'Drupal\Core\Config\Entity\ConfigEntityType')) {
        $options[$entity_type] = $definition->getLabel();
      }
    }

    return $options;
  }

  private function getEntityBundles($entity_type = NULL) {
    $options =[
      -1 => t('Select a Bundle')
    ];

    if ($entity_type !== NULL) {
      $bundles = entity_get_bundles($entity_type);
      foreach($bundles as $bundle => $info) {
        $options[$bundle] = $bundle;
      }
    }
    return $options;
  }

}
