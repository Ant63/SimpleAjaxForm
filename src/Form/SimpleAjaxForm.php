<?php

namespace Drupal\simple_ajax_form\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SimpleAjaxForm.
 *
 * @package Drupal\simple_ajax_form\Form
 */
class SimpleAjaxForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_ajax_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Don't cache this form
    $form_state->setCached(FALSE);

    // Create a containing element for the form items
    // This is where the magic happens.
    $form['submit_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'simple-ajax-form-wrapper'],
    ];

    // The form elements are defined within the container
    // When the form is submitted the ajax submit handler will
    // replace the contents
    $form['submit_wrapper']['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Enter your name'),
      '#maxlength' => 64,
      '#size' => 64,
    );

    $form['submit_wrapper']['actions']['#type'] = 'actions';

    $form['submit_wrapper']['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#name' => 'ajax_submit',
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::simpleAjaxFormCallBack',
        'wrapper' => 'simple-ajax-form-wrapper',
        'method' => 'replace',
        'effect' => 'fade',
      ],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Submit logic here

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation Logic here

  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array
   */
  public function simpleAjaxFormCallback(array &$form, FormStateInterface $form_state) {

    // When this function is called the form is rebuilt. As we don't want to
    // shown the form again the form elements that are not required are unset.

    // Find out what triggered the form if you need to
    $trigger = $form_state->getTriggeringElement();

    // Escape the user input.
    $name = Html::escape($form_state->getValue('name'));

    // Use the returned values to format a return string
    $string = $this->t('@name, welcome to my website', array('@name' => $name));

    $form['submit_wrapper']['test'] = array(
      '#markup' => '<div>' . $string . '</div>',
    );

    // Remove the form elements that are no longer required.
    unset($form['submit_wrapper']['name']);
    unset($form['submit_wrapper']['actions']);

    return $form;
  }

}
