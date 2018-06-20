<?php
/**
 * Created by PhpStorm.
 * User: denzyl
 * Date: 19-6-18
 * Time: 18:48
 */

namespace Av;


class Filter
{
  private $value;
  const STRING = 'string';
  const INTEGER = 'integer';
  const FLOAT = 'float';
  const DECIMAL = 'decimal';
  const EMAIL = 'email';
  private $filters = [
    self::STRING, self::INTEGER, self::FLOAT, self::DECIMAL, self::EMAIL
  ];

  public function __construct($value)
  {
    $this->value = $value;
  }

  /**
   * @param $filter
   * @return mixed
   */
  public function sanitize($filter)
  {
    if (isset($this->filters[$filter]) === false) throw new FilterException();

    $value_copy = $this->value;
    switch ($filter) {
      case Filter::EMAIL:
        if (filter_has_var(FILTER_VALIDATE_EMAIL)) {
          return $value_copy;
        }
        break;
      case Filter::DECIMAL:
        if (is_double($value_copy)) {
          return $value_copy;
        }
        break;
      case Filter::INTEGER:
        if (is_numeric($value_copy)) {
          return $value_copy;
        }
        break;
      case Filter::STRING:
        if (is_string($value_copy)) {
          return $value_copy;
        }
        break;
      case Filter::FLOAT:
        if (is_float($value_copy)) {
          return $value_copy;
        }
        break;
    }

    return $this->value;
  }
}
