<?php

/**
 * @file
 * Contains \Drupal\config_add_another\Form\DrupalVersionForm.
 */

namespace Drupal\config_add_another\Form;

use Drupal\Component\Utility\Html;
use Drupal\config_add_another\DrupalVersionInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Class DrupalVersionForm.
 *
 * @package Drupal\config_add_another\Form
 */
class DrupalVersionForm extends EntityForm {

  /**
   * @var DrupalVersionInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $drupal_version = $this->entity;

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $drupal_version->label(),
      '#description' => $this->t("Label for the Drupal version."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $drupal_version->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\config_add_another\Entity\DrupalVersion::load',
      ),
      '#disabled' => !$drupal_version->isNew(),
    );

    // For simplicity I haven't implemented getters and setters for the custom
    // properties.
    $form['major_version'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Drupal major version'),
      '#default_value' => $drupal_version->get('major_version'),
    );

    $releases = $drupal_version->get('releases');

    // When adding a new release, Drupal uses this empty div to determine where
    // to insert the new form elements into the DOM.
    $wrapper_id = Html::getUniqueId('releases-add-more-wrapper');
    $form['releases'] = array(
      '#tree' => true,
      '#suffix' => '<div id="' . $wrapper_id . '"></div>',
    );

    foreach ($releases as $index => $release) {
      $form['releases'][$index] = $this->releaseFormTemplate();
      $form['releases'][$index]['minor_version']['#default_value'] = $release['minor_version'];
      $form['releases'][$index]['patch_version']['#default_value'] = $release['patch_version'];
      $form['releases'][$index]['security_release']['#default_value'] = $release['security_release'];
    }

    // If the 'add another' button has been clicked, we need to add an empty
    // set of elements to the form. This example doesn't implement deleting a
    // release. That could be handled with explicit remove buttons, or by
    // filtering out elements with empty data.
    $triggering_element = $form_state->getTriggeringElement();
    if ($triggering_element && $triggering_element['#name'] == 'add_release') {
      $form['releases'][] = $this->releaseFormTemplate();
    }

    // 'callback' gets expanded to be an instance of this class. It can also be
    // an array to point to another class instead. 'method' defaults' to
    // 'replace', but to keep this simple I just prepend the new form elements
    // before the wrapper div.
    $form['add_release'] = array(
      '#type' => 'button',
      '#value' => t('Add release'),
      '#name' => 'add_release',
      '#ajax' => array(
        'callback' => '::addMoreAjax',
        'wrapper' => $wrapper_id,
        'method' => 'before',
        'effect' => 'fade',
      ),
    );

    return $form;
  }

  /**
   * Callback to return the form elements to insert.
   *
   * While the above form builder is called during the #ajax request, it doesn't
   * tell Drupal what literal UI elements need to change on the form. This
   * callback takes the very last index in 'releases' and returns it. Note we
   * can't just call the form template method, because the form building process
   * adds a whole set of keys to the form element needed to track validation and
   * submission.
   *
   * @param array $form
   *   The built form array.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   A render element.
   */
  public function addMoreAjax(array $form, FormStateInterface $form_state) {
    // Element::children filters out all of the special '#' properties from the
    // array, leaving us with just our form elements.
    $last_release = end(Element::children($form['releases']));
    return $form['releases'][$last_release];
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var DrupalVersionInterface $drupal_version */
    $drupal_version = $this->entity;
    $status = $drupal_version->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Drupal version.', [
          '%label' => $drupal_version->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Drupal version.', [
          '%label' => $drupal_version->label(),
        ]));
    }
    $form_state->setRedirectUrl($drupal_version->urlInfo('collection'));
  }

  /**
   * Return the form for each release.
   *
   * @return array
   */
  protected function releaseFormTemplate() {
    $release_form = array(
      'minor_version' => array(
        '#type' => 'textfield',
        '#title' => $this->t('Minor version'),
      ),
      'patch_version' => array(
        '#type' => 'textfield',
        '#title' => $this->t('Patch version'),
      ),
      'security_release' => array(
        '#type' => 'checkbox',
        '#title' => 'Security release',
      ),
    );

    return $release_form;
  }

}
