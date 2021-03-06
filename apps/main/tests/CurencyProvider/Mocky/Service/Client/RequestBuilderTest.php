<?php

namespace VirtualCard\Tests\CurrencyProvider\Mocky\Service\Client;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use VirtualCard\CurrencyProvider\Mocky\Service\Client\RequestBuilder;

class RequestBuilderTest extends TestCase
{
    private const MOCK_SERVICE_URL = 'https://www.mocky.io/';
    
    /**
     * @var RequestBuilder
     */
    private $builder;
    
    protected function setUp()
    {
        $this->builder = new RequestBuilder(self::MOCK_SERVICE_URL);
    }
    
    public function testValidRequest(): void
    {
        $request = $this->builder->build();
        
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('gzip', $request->getHeaderLine('Accept-Encoding'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }
}
