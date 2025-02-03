<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class GreetingsTest extends ApiTestCase
{
    use ClockSensitiveTrait;


    protected function setUp(): void
    {
        static::mockTime(new \DateTimeImmutable('2025-02-03T22:42:00+00:00'));
    }


    public function testCreateGreeting(): void
    {
        static::createClient()->request('POST', '/greetings', [
            'json' => [
                'name' => 'Kévin',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Greeting',
            '@type' => 'Greeting',
            'name' => 'Kévin',
            'createdAt' => '2025-02-03T22:42:00+00:00',
            'updatedAt' => '2025-02-03T22:42:00+00:00',
        ]);
    }
}
