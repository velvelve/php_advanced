<?php

namespace GeekBrains\LevelTwo\Blog;

use DateTimeImmutable;

class AuthToken
{
    public function __construct(
        private string $token,
        private UUID $userUuid,
        private DateTimeImmutable $expiresOn
    ) {
    }
    public function token(): string
    {
        return $this->token;
    }
    public function userUuid(): UUID
    {
        return $this->userUuid;
    }
    public function expiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function __toString()
    {
        return $this->token;
    }

    /**
     * Set the value of expiresOn
     *
     * @return  self
     */
    public function setExpiresOn($expiresOn)
    {
        $this->expiresOn = $expiresOn;

        return $this;
    }
}
