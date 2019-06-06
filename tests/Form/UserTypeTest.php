<?php
/**
 * Created by PhpStorm.
 * User: Alicia
 * Date: 2019-06-06
 * Time: 16:52
 */

namespace App\Tests\Form;

use App\Form\UserType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    private $formTest;

    public function setUp()
    {
        $this->formTest = new UserType();
    }

    public function testSubmitValidData(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        $formBuilderMock->expects($this->atLeastOnce())->method('add')->willReturnSelf();

        // Passing the mock as a parameter and an empty array as options as I don't test its use
        $this->formTest->buildForm($formBuilderMock, []);
    }
}