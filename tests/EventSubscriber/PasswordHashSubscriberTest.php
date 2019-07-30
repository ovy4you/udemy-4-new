<?php

namespace Tests;

use App\Entity\User;
use App\EventSubscriber\PasswordHashSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashSubscriberTest extends TestCase
{

    public function testSetPasswordCall()
    {

        $user = new User();
        $user->setName('ovidiu')
            ->setPassword('1234');

        $mockUserPasswordEncoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->getMockForAbstractClass();

        $mockUserPasswordEncoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn('123');

        $mockEvent = $this->getEventMock();

        $mockEvent->expects($this->once())
            ->method('getControllerResult')
            ->willReturn(new User());

        $mockEvent->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->getRequestMock());

        $passwordHashSubscriber = new PasswordHashSubscriber($mockUserPasswordEncoder);
        $passwordHashSubscriber->hasPassword($mockEvent);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|GetResponseForControllerResultEvent
     */
    private function getEventMock()
    {
        $mockEvent = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mockEvent;
    }

    private function getRequestMock()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->getMock();

        $mockRequest->method('getMethod')->willReturn('POST');

        return $mockRequest;
    }
}