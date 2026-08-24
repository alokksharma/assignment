<?php

namespace App\Storage;

use App\Tenant;
use Closure;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use League\Flysystem\FileAttributes;

abstract class TemporaryStorage extends BaseStorage implements Responsable
{
    protected $id;

    protected ?Closure $afterCallback = null;

    public function after(Closure $callback): static
    {
        $this->afterCallback = $callback;

        return $this;
    }

    public function getId()
    {
        if (!$this->id) {
            $this->id = 'tmp_store_' . Str::random(5);
        }

        return $this->id;
    }

    public function forId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Storage the invoice
     * return $this
     */
    public function store(?Closure $then = null)
    {
        $then = $then ?? ($this->afterCallback ?? null);

        $id = $this->getId();

        $file_name = self::insertIdIntoFileName(
            $id,
            $this->getFileNameToStore(),
        );

        Cache::put($id, null);

        $disk = config('filesystems.default');
        $pending_export = $this->init(
            self::getDir() . $file_name,
            $disk,
        )->chain([
            function () use ($id, $file_name) {
                // This ensure that Cache::put sets the correct value for the tenant
                Cache::put($id, $file_name);
            },
        ]);

        if ($then) {
            $pending_export = $pending_export->chain([
                function () use ($then, $id, $file_name) {
                    // This ensure that Cache::get returns the correct value
                    Cache::put($id, $file_name);
                    $then($id, $file_name);
                },
            ]);
        }

        return $this;
    }

    /**
     * Get the user for this invoice
     */
    public function getUrl(): ?string
    {
        return null;
    }

    public static function getUrlForId(string $id): ?string
    {
        $file_name = Cache::get($id);
        if (!$file_name) {
            return null;
        }

        return self::getUrlForFile($file_name);
    }

    public static function getDir(): string
    {
        return 'temporary-storage/';
    }

    public static function cleanup(?int $before = null): int
    {
        $before = $before ?: now()->subMinutes(10)->getTimestamp();
        $disk = self::disk();
        $deleted_files_count = 0;
        // cleanup the temp files, created 1 hours ago
        collect($disk->listContents(self::getDir(), true))->each(function (
            FileAttributes $file,
        ) use ($before, $disk, &$deleted_files_count) {
            if ($file->isFile() && $file->lastModified() < $before) {
                try {
                    $path = $file->path();
                    $file_name = last(explode('/', $path));
                    $storage_id = self::retrieveIdFromFileName($file_name);
                    // delete the file
                    $disk->delete($file->path());
                    // cleanup the cache as well
                    Cache::delete($storage_id);
                    $deleted_files_count++;
                } catch (\Exception $e) {
                    // report and continue
                    report($e);
                }
            }
        });

        return $deleted_files_count;
    }

    protected static function insertIdIntoFileName(
        string $id,
        string $file_name,
    ): string {
        return $id . '__' . $file_name;
    }

    protected static function retrieveIdFromFileName(string $file_name): string
    {
        // reversal of insertIdIntoFileName
        return explode('__', $file_name, 2)[0];
    }

    abstract public function init(
        string $filePath,
        string $disk,
    ): PendingDispatch;

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $this->store();

        $url = self::getUrlForId($this->getId());

        return response()->json([
            'data' => [
                'id' => $this->getId(),
                'status' => $url ? 'completed' : 'pending',
                'url' => $url,
            ],
        ]);
    }
}
