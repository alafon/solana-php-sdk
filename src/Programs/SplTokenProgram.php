<?php

namespace Tighten\SolanaPhpSdk\Programs;

use Tighten\SolanaPhpSdk\Program;
use Tighten\SolanaPhpSdk\PublicKey;

class SplTokenProgram extends Program
{
    public const SOLANA_TOKEN_PROGRAM_ID = 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA';

    // https://spl.solana.com/associated-token-account#finding-the-associated-token-account-address
    public const SPL_ASSOCIATED_TOKEN_ACCOUNT_PROGRAM_ID = 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL';

    /**
     * @return mixed
     */
    public function getTokenAccountsByOwner(string $pubKey)
    {
        return $this->client->call('getTokenAccountsByOwner', [
            $pubKey,
            [
                'programId' => self::SOLANA_TOKEN_PROGRAM_ID,
            ],
            [
                'encoding' => 'jsonParsed',
            ],
        ]);
    }

    /**
     * The associated token account for a given wallet address is simply a program-derived account consisting of the wallet address itself and the token mint.
     * @see https://spl.solana.com/associated-token-account#finding-the-associated-token-account-address
     */
    public function findAssociatedTokenAddress(PublicKey $walletAddress, PublicKey $tokenMintAddress): string
    {
        return PublicKey::findProgramAddress([
            $walletAddress->toBuffer(),
            (new PublicKey(self::SOLANA_TOKEN_PROGRAM_ID))->toBuffer(),
            $tokenMintAddress->toBuffer(),
        ], new PublicKey(self::SPL_ASSOCIATED_TOKEN_ACCOUNT_PROGRAM_ID))[0];
    }
}
