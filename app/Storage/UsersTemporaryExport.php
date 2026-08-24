<?php

namespace App\Storage;

use Closure;
use App\Jobs\ProcessUsersExport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersTemporaryExport
{
    protected ?string $id = null;

    protected ?Closure $afterCallback = null;

    public function after(Closure $callback): static
    {
        $this->afterCallback = $callback;

        return $this;
    }

    public function getId(): string
    {
        if (! $this->id) {
            $this->id = 'tmp_export_' . Str::random(8);
        }

        return $this->id;
    }

    public function store(): static
    {
        $id = $this->getId();

        Cache::put($id, [
            'status' => 'pending',
            'url' => null,
        ], now()->addMinutes(30));

        ProcessUsersExport::dispatch($id);

        return $this;
    }

    public static function completed(
        string $id,
        string $file
    ): void {

        Cache::put($id, [
            'status' => 'completed',
            'url' => asset(
                Storage::disk('public')->url($file)
            ),
        ], now()->addMinutes(30));
    }

    public static function failed(string $id): void
    {
        Cache::put($id, [
            'status' => 'failed',
            'url' => null,
        ], now()->addMinutes(30));
    }

    public static function get(string $id): ?array
    {
        return Cache::get($id);
    }
}
