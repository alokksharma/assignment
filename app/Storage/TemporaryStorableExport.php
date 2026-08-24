<?php

namespace App\Storage;

interface TemporaryStorableExport
{
    public function getFileName(): string;
}
