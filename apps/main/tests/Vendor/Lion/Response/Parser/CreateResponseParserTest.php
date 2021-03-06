<?php

namespace VirtualCard\Tests\Vendor\Lion\Response\Parser;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use VirtualCard\Vendor\Lion\Response\Parser\CreateResponseParser;

class CreateResponseParserTest extends TestCase
{
    public function testValid(): void
    {
        $response = '{"cardNumber": "4113601974804222","cvc": 239,"referenceCode": "THATISVENDR2REFCODE"}';
        
        $result = CreateResponseParser::parse($response);
        
        $this->assertSame('THATISVENDR2REFCODE', $result->getReference());
        $this->assertSame('4113601974804222', $result->getCardNumber());
        $this->assertSame('239', $result->getCvc());
    }
    
    public function testInvalid(): void
    {
        $response = '{someinvalidresponse},,';
        
        $this->expectException(InvalidArgumentException::class);
        
        CreateResponseParser::parse($response);
    }
}
