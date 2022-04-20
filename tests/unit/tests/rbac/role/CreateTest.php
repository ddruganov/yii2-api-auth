<?php

namespace tests\unit\tests\rbac\role;

use ddruganov\Yii2ApiAuth\forms\rbac\role\CreateForm;
use tests\unit\BaseUnitTest;

final class CreateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new CreateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testSameName()
    {
        $existingRole = $this->getFaker()->role();

        $form = new CreateForm([
            'name' => $existingRole->getName(),
            'description' => $this->getFaker()->text()
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name'],
            noErrorKeys: ['permissionIds', 'description']
        );
    }
}
