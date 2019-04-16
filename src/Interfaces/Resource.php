<?php

namespace Tasteful\Interfaces;

use Tasteful\Request;
use Tasteful\Response;

interface Resource
{
    public function delete(Request $request): Response;
    public function get(Request $request): Response;
    public function post(Request $request): Response;
    public function put(Request $request): Response;
}
