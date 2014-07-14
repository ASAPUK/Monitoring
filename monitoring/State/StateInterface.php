<?php

namespace Monitoring\State;

interface StateInterface
{
    public function verifyError();
    public function sendError();
}