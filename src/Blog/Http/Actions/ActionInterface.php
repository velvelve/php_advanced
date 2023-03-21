<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions;

use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}
