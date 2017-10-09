<?php

namespace Drupal\ame_stencil\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'ame_text' formatter.
 *
 * @FieldFormatter(
 *   id = "ame_text",
 *   label = @Translation("ame-text"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class AmeTextFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a AmeTextFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $entity = $items->getEntity();
    $resource_url = _ame_stencil_get_resource_url($entity);
    $name = $this->fieldDefinition->getName();

    foreach ($items as $delta => $item) {
      if ($resource_url) {
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'ame-text',
          '#value' => $item->value,
          '#attributes' => [
            'ame-handler' => 'rest',
            'ame-resource' => $resource_url,
            'ame-path' => $name . '.' . $delta . '.value',
          ],
        ];
      }
      else {
        $elements[$delta] = [
          '#markup' => $item->value,
        ];
      }
    }
    return $elements;
  }

}
