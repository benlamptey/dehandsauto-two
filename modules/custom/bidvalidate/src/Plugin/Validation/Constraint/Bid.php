<?php

namespace Drupal\bidvalidate\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Ensures the correct value is submitted..
 *
 * @Constraint(
 *   id = "BidValidator",
 *   label = @Translation("Bid Validator", context = "Validation"),
 *   type = "string"
 * )
 */
class Bid extends Constraint {

  // The message that will be shown if the value is not an integer.
  public $errorOne = ',-  Gwrgerginstens € %value';

  // The message that will be shown if the value is not unique.
  public $errorTwo = 'Het laatste bod was € %value ,-  graag een hoger bod en opgaande met minstens € %valueTwo';

}
