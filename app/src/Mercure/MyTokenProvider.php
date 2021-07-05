<?php

namespace App\Mercure;

use Symfony\Component\Mercure\Jwt\TokenProviderInterface;

final class MyTokenProvider implements TokenProviderInterface
{
    public function getJwt(): string
    {
        return 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOltdfX0.ONTpwPGU4_9glBi2xqMuOjvSC9qqy45ZQ_h1y3BraH8';
    }
}