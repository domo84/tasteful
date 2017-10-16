<?php

namespace Sunnyvale\REST\Interfaces;

use Sunnyvale\REST\Request;
use Sunnyvale\REST\Response;

interface Resource
{
    public function delete(Request $request): Response;
    public function get(Request $request): Response;
    public function post(Request $request): Response;
    public function put(Request $request): Response;
}
