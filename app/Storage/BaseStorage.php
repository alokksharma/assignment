<?php

namespace App\Storage;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

abstract class BaseStorage
{
    /**
     * User that has requested the creation
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Timezone offset
     *
     * @var int
     */
    protected $timezone_offset = -330;

    /**
     * Visibility of vouchers
     */
    public const VISIBILITY = 'public';

    /**
     * Set the timezone
     * return $this
     */
    public function inTimezone(?int $timezone_offset = -330): self
    {
        $this->timezone_offset = $timezone_offset;

        return $this;
    }

    /**
     * Get the url for for a given file name
     */
    public static function getUrlForFile(?string $file_name = null): ?string
    {
        if (!$file_name) {
            return null;
        }

        return self::disk()->url(static::getDir() . $file_name);
    }

    /**
     * Store with a file and content
     */
    public static function put(string $file_name, $content)
    {
        $file_uploaded = false;
        if (is_string($content) || $content instanceof Response) {
            $file_uploaded = self::disk()->put(
                static::getDir() . $file_name,
                $content,
                [
                    'visibility' => self::VISIBILITY,
                ],
            );
        } else {
            $file_uploaded = self::disk()->putFileAs(
                static::getDir(),
                $content,
                $file_name,
                [
                    'visibility' => self::VISIBILITY,
                ],
            );
        }

        if (!$file_uploaded) {
            throw new \Exception(
                'Unable to put file "' .
                    $file_name .
                    '" under directory "' .
                    static::getDir() .
                    '"',
            );
        }

        return $file_name;
    }

    /**
     * Delete with a file and content
     *
     * @params string|string[] $file_name
     */
    public static function delete($file_name): void
    {
        $dir = static::getDir();
        $files = Arr::wrap($file_name);
        self::disk()->delete(
            array_map(function ($file) use ($dir) {
                return $dir . $file;
            }, $files),
        );
    }

    /**
     * Get the storage dist
     */
    public static function disk()
    {
        return Storage::disk();
    }

    /**
     * Check if the file exists on the storage
     */
    public static function exists($file_name)
    {
        return self::disk()->exists(static::getDir() . $file_name);
    }

    /**
     * Fake the disk for testing purpose
     */
    public static function fake()
    {
        Storage::fake(config('filesystems.cloud'));
    }

    /**
     * Set the user of the voucher generation
     */
    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the directory to store
     */
    abstract public static function getDir(): string;

    /**
     * Get url
     */
    abstract public function getUrl(): ?string;

    /**
     * Get the file name to store
     */
    abstract public function getFileNameToStore(): string;
}
