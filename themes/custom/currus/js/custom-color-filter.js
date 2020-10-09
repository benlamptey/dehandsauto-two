/**
 * @file
 * Attaches behaviors for Drupal's color field.
 */

(function ($, Drupal) {

    'use strict';

    /**
     * Enables box widget on color elements.
     *
     * @type {Drupal~behavior}
     *
     * @prop {Drupal~behaviorAttach} attach
     *   Attaches a box widget to a color input element.
     */
    Drupal.behaviors.custom_color_filter = {
        attach: function (context, settings) {

            $('#edit-field-kleur-picker-color--2--wrapper input', context).each(function () {
                var color_hex = $(this).attr('value');
                $(this).next('label').css('background-color', '#' + color_hex);
                $(this).next('label').html('');
            });

            // When checkbox is clicked
            $('#edit-field-kleur-picker-color--2--wrapper .custom-color-checkbox label', context).once().click(function() {
                $(this).toggleClass('custom-color-active');
            });
        },
    };

})(jQuery, Drupal);
