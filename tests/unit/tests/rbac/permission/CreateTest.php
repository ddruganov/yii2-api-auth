<?php

namespace tests\unit\tests\rbac\permission;

use ddruganov\Yii2ApiAuth\forms\rbac\permission\CreateForm;
use tests\unit\BaseUnitTest;

final class CreateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new CreateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'appUuid']
        );
    }

    public function testSameName()
    {
        $existingPermission = $this->getFaker()->permission();

        $form = new CreateForm([
            'name' => $existingPermission->getName(),
            'description' => $this->getFaker()->text(),
            'appUuid' => $existingPermission->getAppUuid()
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'appUuid'],
            noErrorKeys: ['description']
        );
    }
}
