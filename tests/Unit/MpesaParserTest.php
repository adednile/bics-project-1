<?php

namespace Tests\Unit;

use App\Services\MpesaSMSParser;
use PHPUnit\Framework\TestCase;

class MpesaParserTest extends TestCase
{
    public function test_parser_extracts_amount_sender_code_and_date(): void
    {
        $parser = new MpesaSMSParser();
        $message = 'Thank you for buying airtime. Amount: 2500.00 from John Doe. Ref: QWERTY. Date: 2026-06-15';

        $result = $parser->parse($message);

        $this->assertSame('2500.00', $result['amount']);
        $this->assertSame('John Doe', $result['sender']);
        $this->assertSame('QWERTY', $result['transaction_code']);
        $this->assertSame('2026-06-15', $result['date']);
    }
}
