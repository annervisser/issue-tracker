<?php

declare(strict_types=1);

namespace SharedTest\Infra\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events as ORMEvents;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use PHPUnit\Framework\TestCase;
use Shared\Infra\Doctrine\DoctrineTrackingPolicySubscriber;

/** @covers \Shared\Infra\Doctrine\DoctrineTrackingPolicySubscriber */
class DoctrineTrackingPolicySubscriberTest extends TestCase
{
    public function testOnlySubscribedToRightEvents(): void
    {
        $subscriber = new DoctrineTrackingPolicySubscriber();
        self::assertEquals([ORMEvents::loadClassMetadata], $subscriber->getSubscribedEvents());
    }

    public function testLoadClassMetadata(): void
    {
        $testClass     = new class {
        };
        $classMetadata = new ClassMetadata($testClass::class);
        $eventArgs     = new LoadClassMetadataEventArgs(
            $classMetadata,
            $this->createMock(EntityManager::class)
        );
        $subscriber    = new DoctrineTrackingPolicySubscriber();
        $subscriber->loadClassMetadata($eventArgs);
        self::assertSame(ClassMetadataInfo::CHANGETRACKING_DEFERRED_EXPLICIT, $classMetadata->changeTrackingPolicy);
    }
}
