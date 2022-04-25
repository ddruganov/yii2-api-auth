<?php

namespace tests\unit\tests\app;

use ddruganov\Yii2ApiAuth\forms\app\DeleteForm;
use tests\unit\BaseUnitTest;

final class DeleteTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new DeleteForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['uuid']
        );
    }

    public function testValid()
    {
        $form = new DeleteForm(['uuid' => $this->getFaker()->app()->getUuid()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
    }
}
