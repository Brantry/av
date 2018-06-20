<?php
/**
 * Created by PhpStorm.
 * User: denzyl
 * Date: 20-6-18
 * Time: 20:34
 */

use Av\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{

  public function testSanitize()
  {
    $filter = new Filter(1);
    $this->assertEquals(null, $filter->sanitize(Filter::EMAIL));

    $filter = new Filter("email@live.nl");
    $this->assertEquals("email@live.nl", $filter->sanitize(Filter::EMAIL));

    $filter = new Filter(10.0);
    $this->assertEquals(10.0, $filter->sanitize(Filter::FLOAT));

    $filter = new Filter("hello");
    $this->assertEquals(null, $filter->sanitize(Filter::DECIMAL));
  }
}
