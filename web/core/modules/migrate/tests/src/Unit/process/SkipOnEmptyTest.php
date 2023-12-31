<?php

declare(strict_types=1);

namespace Drupal\Tests\migrate\Unit\process;

use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\migrate\process\SkipOnEmpty;

/**
 * Tests the skip on empty process plugin.
 *
 * @group migrate
 * @coversDefaultClass \Drupal\migrate\Plugin\migrate\process\SkipOnEmpty
 */
class SkipOnEmptyTest extends MigrateProcessTestCase {

  /**
   * @covers ::process
   */
  public function testProcessSkipsOnEmpty() {
    $configuration['method'] = 'process';
    $this->expectException(MigrateSkipProcessException::class);
    (new SkipOnEmpty($configuration, 'skip_on_empty', []))
      ->transform('', $this->migrateExecutable, $this->row, 'destination_property');
  }

  /**
   * @covers ::process
   */
  public function testProcessBypassesOnNonEmpty() {
    $configuration['method'] = 'process';
    $value = (new SkipOnEmpty($configuration, 'skip_on_empty', []))
      ->transform(' ', $this->migrateExecutable, $this->row, 'destination_property');
    $this->assertSame(' ', $value);
  }

  /**
   * @covers ::row
   */
  public function testRowSkipsOnEmpty() {
    $configuration['method'] = 'row';
    $this->expectException(MigrateSkipRowException::class);
    (new SkipOnEmpty($configuration, 'skip_on_empty', []))
      ->transform('', $this->migrateExecutable, $this->row, 'destination_property');
  }

  /**
   * @covers ::row
   */
  public function testRowBypassesOnNonEmpty() {
    $configuration['method'] = 'row';
    $value = (new SkipOnEmpty($configuration, 'skip_on_empty', []))
      ->transform(' ', $this->migrateExecutable, $this->row, 'destination_property');
    $this->assertSame(' ', $value);
  }

  /**
   * Tests that a skip row exception without a message is raised.
   *
   * @covers ::row
   */
  public function testRowSkipWithoutMessage() {
    $configuration = [
      'method' => 'row',
    ];
    $process = new SkipOnEmpty($configuration, 'skip_on_empty', []);
    $this->expectException(MigrateSkipRowException::class);
    $process->transform('', $this->migrateExecutable, $this->row, 'destination_property');
  }

  /**
   * Tests that a skip row exception with a message is raised.
   *
   * @covers ::row
   */
  public function testRowSkipWithMessage() {
    $configuration = [
      'method' => 'row',
      'message' => 'The value is empty',
    ];
    $process = new SkipOnEmpty($configuration, 'skip_on_empty', []);
    $this->expectException(MigrateSkipRowException::class);
    $this->expectExceptionMessage('The value is empty');
    $process->transform('', $this->migrateExecutable, $this->row, 'destination_property');
  }

}
