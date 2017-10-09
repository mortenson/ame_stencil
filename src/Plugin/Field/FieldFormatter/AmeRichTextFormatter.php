<?php

namespace Drupal\ame_stencil\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ame_rich_text' formatter.
 *
 * @FieldFormatter(
 *   id = "ame_rich_text",
 *   label = @Translation("Ame Rich Text"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class AmeRichTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $entity = $items->getEntity();
    $resource_url = _ame_stencil_get_resource_url($entity);
    $name = $this->fieldDefinition->getName();

    foreach ($items as $delta => $item) {
      $text = [
        '#type' => 'processed_text',
        '#text' => $item->value,
        '#format' => $item->format,
        '#langcode' => $item->getLangcode(),
      ];
      $elements[$delta] = [
        '#type' => 'container',
        'value' => [
          '#type' => 'html_tag',
          '#tag' => 'ame-rich-text',
          '#value' => render($text),
          '#attributes' => [
            'ame-handler' => 'rest',
            'ame-resource' => $resource_url,
            'ame-path' => $name . '.' . $delta . '.value',
          ],
        ],
        'format' => [
          '#type' => 'html_tag',
          '#tag' => 'ame-value',
          '#attributes' => [
            'ame-handler' => 'rest',
            'ame-resource' => $resource_url,
            'ame-path' => $name . '.' . $delta . '.format',
            'return' => $item->format,
          ],
        ],
      ];
    }

    return $elements;
  }

}
