<?php

namespace Alexpr\IssuesHandler\Test\Unit\Helper;

use Alexpr\IssuesHandler\Helper\InputValidator;
use PHPUnit\Framework\TestCase;
use Zend\Validator\EmailAddress;

class ValidatorTest extends TestCase
{
    private $validatorClass;

    private $zendMock;

    public function testCorrectInput()
    {
        $this->zendMock->expects($this->any())
            ->method('isValid')
            ->with('hello@gmail.com')
            ->willReturn(true);

        $data = [
            'name' => 'test',
            'email' => 'hello@gmail.com',
            'issue_text' => 'some text'
        ];

        $return = $this->validatorClass->validate($data);
        $this->assertEquals(true, $return);
    }

    public function testIncorrectInput()
    {
        $this->zendMock->expects($this->any())
            ->method('isValid')
            ->with('nop')
            ->willReturn(false);
        $data = [
            'name' => 'test',
            'email' => 'nop',
            'issue_text' => 'test'
        ];
        $return = $this->validatorClass->validate($data);
        $this->assertEquals(false, $return);
    }

    public function testEmptyInput()
    {
        $this->zendMock->expects($this->any())
            ->method('isValid')
            ->with('')
            ->willReturn(false);
        $data = [
            'name' => '',
            'email' => '',
            'issue_text' => ''
        ];
        $return = $this->validatorClass->validate($data);
        $this->assertEquals(false, $return);
    }

    protected function setUp()
    {
        $this->zendMock = $this->createMock(EmailAddress::class);
        $this->validatorClass = new InputValidator($this->zendMock);
    }

    protected function tearDown()
    {
        $this->validatorClass = null;
        $this->zendMock = null;
    }
}
