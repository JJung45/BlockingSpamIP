<?php

namespace Junghakyoung\BlockingKisaSpam;

use Faker\Provider\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Junghakyoung\BlockingKisaSpam\Exceptions\WrongIPException;
use PHPUnit\Framework\TestCase;
use Faker\Provider\Internet;


class BlockingTest extends TestCase
{

    protected function getGuzzle(): Client
    {
        $mock = new MockHandler([
            new Response(200, [], "<span class='orange'>등록되어 있지 않습니다.</span>"),
        ]);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * @throws \Exception
     */
    public function testCanBeCreatedFromValidIP(): void
    {
        $this->assertTrue((new BlockingSpamIP($this->getGuzzle()))->isOk(Internet::localIpv4()));
    }

    /**
     * @throws \Exception
     */
    public function testWrongIP(): void
    {
        $this->expectException(WrongIPException::class);
        (new BlockingSpamIP($this->getGuzzle()))->isOk(Uuid::randomAscii());
    }
}