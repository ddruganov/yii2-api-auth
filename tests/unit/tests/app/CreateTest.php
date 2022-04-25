<?php

namespace tests\unit\tests\app;

use ddruganov\Yii2ApiAuth\forms\app\CreateForm;
use tests\unit\BaseUnitTest;

final class CreateTest extends BaseUnitTest
{
    public function testSameAlias()
    {
        $existingApp = $this->getFaker()->app();

        $form = new CreateForm();
        $form->setAttributes(['alias' => $existingApp->getAlias()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'alias', 'audience', 'baseUrl']
        );
    }
}
