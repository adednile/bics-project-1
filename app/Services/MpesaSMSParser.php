<?php

namespace App\Services;

class MpesaSMSParser
{
    public function parse(string $message): array
    {
        $normalized = trim($message);

        $amount = null;
        preg_match('/([0-9]+(?:\.[0-9]{1,2})?)/', $normalized, $amountMatches);
        if (! empty($amountMatches[1])) {
            $amount = (float) $amountMatches[1];
        }

        preg_match('/from\s+([A-Za-z0-9\s.-]+?)(?:\.\s+Ref|\s+Ref|$)/i', $normalized, $senderMatches);
        $sender = $senderMatches[1] ?? null;

        preg_match('/(\b[A-Z0-9]{5,}\b)/', $normalized, $codeMatches);
        $transactionCode = $codeMatches[1] ?? null;

        preg_match('/(\d{4}-\d{2}-\d{2})/', $normalized, $dateMatches);
        $date = $dateMatches[1] ?? null;

        return [
            'amount' => number_format($amount ?? 0, 2, '.', ''),
            'sender' => trim((string) $sender),
            'transaction_code' => trim((string) $transactionCode),
            'date' => $date,
            'message' => $normalized,
        ];
    }
}
