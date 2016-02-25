<?php

/**
 * @file
 * Contains \Drupal\config_add_another\Entity\DrupalVersion.
 */

namespace Drupal\config_add_another\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\config_add_another\DrupalVersionInterface;

/**
 * Defines the Drupal version entity.
 *
 * @ConfigEntityType(
 *   id = "drupal_version",
 *   label = @Translation("Drupal version"),
 *   handlers = {
 *     "list_builder" = "Drupal\config_add_another\DrupalVersionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\config_add_another\Form\DrupalVersionForm",
 *       "edit" = "Drupal\config_add_another\Form\DrupalVersionForm",
 *       "delete" = "Drupal\config_add_another\Form\DrupalVersionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\config_add_another\DrupalVersionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "drupal_version",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/drupal_version/{drupal_version}",
 *     "add-form" = "/admin/structure/drupal_version/add",
 *     "edit-form" = "/admin/structure/drupal_version/{drupal_version}/edit",
 *     "delete-form" = "/admin/structure/drupal_version/{drupal_version}/delete",
 *     "collection" = "/admin/structure/drupal_version"
 *   }
 * )
 */
class DrupalVersion extends ConfigEntityBase implements DrupalVersionInterface {

  /**
   * The Drupal version ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Drupal version label.
   *
   * @var string
   */
  protected $label;

  /**
   * @var integer
   */
  protected $major_version;

  /**
   * @var array
   */
  protected $releases;
}
