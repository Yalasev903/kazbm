<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\BaseFileUpload;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUpload extends \Filament\Forms\Components\FileUpload
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (BaseFileUpload $component, string | array | null $state): void {
            if (blank($state)) {
                $component->state([]);

                return;
            }

            $shouldFetchFileInformation = $component->shouldFetchFileInformation();

            $files = collect(Arr::wrap($state))
                ->filter(static function (string $file) use ($component, $shouldFetchFileInformation): bool {
                    if (blank($file)) {
                        return false;
                    }

                    if (! $shouldFetchFileInformation) {
                        return true;
                    }

                    try {
                        return $component->getDisk()->exists($file);
                    } catch (UnableToCheckFileExistence $exception) {
                        return false;
                    }
                })
                ->mapWithKeys(static fn (string $file): array => [((string) Str::uuid()) => $file])
                ->all();

            $component->state($files);
        });

        $this->afterStateUpdated(static function (BaseFileUpload $component, $state) {
            if ($state instanceof TemporaryUploadedFile) {
                return;
            }

            if (blank($state)) {
                return;
            }

            if (is_array($state)) {
                return;
            }

            $component->state([(string) Str::uuid() => $state]);
        });

        $this->beforeStateDehydrated(static function (BaseFileUpload $component): void {
            $component->saveUploadedFiles();
        });

        $this->dehydrateStateUsing(static function (BaseFileUpload $component, ?array $state): string | array | null | TemporaryUploadedFile {
            $files = array_values($state ?? []);

            if ($component->isMultiple()) {
                return $files;
            }

            return $files[0] ?? null;
        });

        $this->getUploadedFileUsing(static function (BaseFileUpload $component, string $file, string | array | null $storedFileNames): ?array {
            /** @var FilesystemAdapter $storage */
            $storage = $component->getDisk();

            $shouldFetchFileInformation = $component->shouldFetchFileInformation();

            if ($shouldFetchFileInformation) {
                try {
                    if (! $storage->exists($file)) {
                        return null;
                    }
                } catch (UnableToCheckFileExistence $exception) {
                    return null;
                }
            }

            $url = null;

            if ($component->getVisibility() === 'private') {
                try {
                    $url = $storage->temporaryUrl(
                        $file,
                        now()->addMinutes(5),
                    );
                } catch (Throwable $exception) {
                    // This driver does not support creating temporary URLs.
                }
            }

            $url ??= $storage->url($file);

            return [
                'name' => ($component->isMultiple() ? ($storedFileNames[$file] ?? null) : $storedFileNames) ?? basename($file),
                'size' => $shouldFetchFileInformation ? $storage->size($file) : 0,
                'type' => $shouldFetchFileInformation ? $storage->mimeType($file) : null,
                'url' => $url,
            ];
        });

        $this->getUploadedFileNameForStorageUsing(static function (BaseFileUpload $component, TemporaryUploadedFile $file) {
            return $component->shouldPreserveFilenames() ? $file->getClientOriginalName() : (Str::ulid() . '.' . $file->getClientOriginalExtension());
        });

        $this->saveUploadedFileUsing(static function (BaseFileUpload $component, TemporaryUploadedFile $file): ?string {
            try {
                if (! $file->exists()) {
                    return null;
                }
            } catch (UnableToCheckFileExistence $exception) {
                return null;
            }

            if (in_array('image/*', $component->getAcceptedFileTypes())
                && !in_array($file->getClientOriginalExtension(), ['svg', 'webp'])) {
                $fileName = $component->getUploadedFileNameForStorage($file);
                $fileDirectory = storage_path('app/public/'). $component->getDirectory();
                if (!file_exists($fileDirectory)) {
                    mkdir($fileDirectory);
                }
                Image::make($file->path())
                    ->encode('webp')
                    ->save($fileDirectory .'/'. pathinfo($fileName, PATHINFO_FILENAME) .'.webp');
            }

            if (
                $component->shouldMoveFiles() &&
                ($component->getDiskName() == (fn (): string => $this->disk)->call($file))
            ) {
                $newPath = trim($component->getDirectory() . '/' . $component->getUploadedFileNameForStorage($file), '/');

                $component->getDisk()->move((fn (): string => $this->path)->call($file), $newPath);

                return $newPath;
            }

            $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

            return $file->{$storeMethod}(
                $component->getDirectory(),
                $component->getUploadedFileNameForStorage($file),
                $component->getDiskName(),
            );
        });
    }
}
