<?php

namespace Drupal\bidvalidate\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the UniqueInteger constraint.
 */
class BidValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      $this->context->addViolation($constraint->errorOne, ['%value' => $item->value]);

      // First check if the value is an integer.
    }
  }


}
