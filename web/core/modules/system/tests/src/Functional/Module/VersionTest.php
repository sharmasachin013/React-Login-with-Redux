<?php

declare(strict_types=1);

namespace Drupal\Tests\system\Functional\Module;

/**
 * Tests module version dependencies.
 *
 * @group Module
 * @group #slow
 */
class VersionTest extends ModuleTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests version dependencies.
   */
  public function testModuleVersions(): void {
    $dependencies = [
      // Alternating between being compatible and incompatible with
      // 8.x-2.4-beta3.
      // The first is always a compatible.
      'common_test',
      // Branch incompatibility.
      'common_test (1.x)',
      // Branch compatibility.
      'common_test (2.x)',
      // Another branch incompatibility.
      'common_test (>2.x)',
      // Another branch compatibility.
      'common_test (<=2.x)',
      // Another branch incompatibility.
      'common_test (<2.x)',
      // Another branch compatibility.
      'common_test (>=2.x)',
      // Nonsense, misses a dash. Incompatible with everything.
      'common_test (=8.x2.x, >=2.4)',
      // Core version is optional. Compatible.
      'common_test (=8.x-2.x, >=2.4-alpha2)',
      // Test !=, explicitly incompatible.
      'common_test (=2.x, !=2.4-beta3)',
      // Three operations. Compatible.
      'common_test (=2.x, !=2.3, <2.5)',
      // Testing extra version. Incompatible.
      'common_test (<=2.4-beta2)',
      // Testing extra version. Compatible.
      'common_test (>2.4-beta2)',
      // Testing extra version. Incompatible.
      'common_test (>2.4-rc0)',
    ];
    \Drupal::state()->set('system_test.dependencies', $dependencies);
    $n = count($dependencies);
    for ($i = 0; $i < $n; $i++) {
      $this->drupalGet('admin/modules');
      if ($i % 2 == 0) {
        $this->assertSession()->fieldEnabled('edit-modules-module-test-enable');
      }
      else {
        $this->assertSession()->fieldDisabled('edit-modules-module-test-enable');
      }
    }
  }

}
