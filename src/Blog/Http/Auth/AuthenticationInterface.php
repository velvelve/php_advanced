<?php

namespace GeekBrains\LevelTwo\Blog\Http\Auth;

use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\User;

interface AuthenticationInterface
{
    public function user(Request $request): User;
}
