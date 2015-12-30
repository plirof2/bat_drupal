<?php

/**
 * @file
 * Class CalendarResponse
 */

namespace Drupal\bat;

use Drupal\bat\Unit;
use Drupal\bat\Constraint;

/**
 * A CalendarResponse contains the units that are matched or missed following
 * a search, together with the reason they are matched or missed.
 */
class CalendarResponse {

  const VALID_STATE = 'valid_state';
  const INVALID_STATE = 'invalid_state';

  /**
   * @var array
   */
  public $included_set;

  /**
   * @var array
   */
  public $excluded_set;

  public $start_date;

  public $end_date;

  public $valid_states;

  /**
   * @param $included
   * @param $excluded
   */
  public function __construct(\DateTime $start_date, \DateTime $end_date, $valid_states, $included = array(), $excluded = array()) {
    $this->start_date = $start_date;
    $this->end_date = $end_date;
    $this->valid_states = $valid_states;
    $this->included = $included;
    $this->excluded = $excluded;
  }

  /**
   * @param $unit
   * @param $reason
   */
  public function addMatch(Unit $unit, $reason = '') {
    $this->included_set[$unit->getUnitId()] = array(
      'unit' => $unit,
      'reason' => $reason,
    );
  }

  /**
   * @param $unit
   * @param $reason
   */
  public function addMiss(Unit $unit, $reason = '') {
    $this->excluded_set[$unit->getUnitId()] = array(
      'unit' => $unit,
      'reason' => $reason,
    );
  }

  /**
   * @return
   */
  public function getIncluded() {
    return $this->included_set;
  }

  /**
   * @return
   */
  public function getExcluded() {
    return $this->excluded_set;
  }

  /**
   * @return
   */
  public function getStartDate() {
    return $this->start_date;
  }

  /**
   * @return
   */
  public function getEndDate() {
    return $this->end_date;
  }

  /**
   * @param $unit
   * @param $reason
   *
   * @return
   */
  public function removeFromMatched(Unit $unit, $reason = '') {
    if (isset($this->included_set[$unit->getUnitId()])) {
      // Remove a unit from matched and add to the missed set
      unset($this->included_set[$unit->getUnitId()]);
      $this->addMiss($unit, $reason);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public function applyConstraints($constraints) {
    foreach ($constraints as $constraint) {
      $constraint->applyConstraint($this);
    }
  }

}
