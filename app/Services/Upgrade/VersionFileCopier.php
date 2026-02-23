<?php

declare(strict_types=1);

namespace App\Services\Upgrade;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class VersionFileCopier
{
    private array $copiedFiles = [];
    private array $skippedFiles = [];
    private array $failedFiles = [];
    private array $errors = [];

    private bool $force = false;
    private bool $preservePermissions = true;
    /**
     * @var callable|null
     */
    private $progressCallback = null;

    /**
     * Set whether to overwrite existing files
     */
    public function setForce(bool $force): self
    {
        $this->force = $force;
        return $this;
    }

    /**
     * Set whether to preserve file permissions
     */
    public function setPreservePermissions(bool $preserve): self
    {
        $this->preservePermissions = $preserve;
        return $this;
    }

    /**
     * Set a callback for progress reporting
     */
    public function onProgress(callable $callback): self
    {
        $this->progressCallback = $callback;
        return $this;
    }

    /**
     * Copy files from a version directory to their respective folders
     *
     * @param string $version Version name (e.g., v1.2)
     * @param string|null $basePath Base path (defaults to base_path())
     * @return array Operation summary
     * @throws Exception
     */
    public function copy(string $version, ?string $basePath = null): array
    {
        $this->resetStats();

        $basePath = $basePath ?? base_path();
        $versionPath = $basePath . DIRECTORY_SEPARATOR . $version;

        $this->validateVersionPath($versionPath);

        $files = $this->getFiles($versionPath);

        if (empty($files)) {
            return $this->getSummary();
        }

        foreach ($files as $index => $file) {
            $this->processFile($file, $versionPath, $basePath);
            
            if ($this->progressCallback) {
                call_user_func($this->progressCallback, $index + 1, count($files), $file);
            }
        }

        $this->logOperation($version);

        return $this->getSummary();
    }

    /**
     * Copy a specific file
     *
     * @param string $sourcePath Full source file path
     * @param string $destinationPath Full destination file path
     * @return bool
     */
    public function copyFile(string $sourcePath, string $destinationPath): bool
    {
        try {
            if (!File::exists($sourcePath)) {
                throw new Exception("Source file does not exist: {$sourcePath}");
            }

            if (!File::isFile($sourcePath)) {
                throw new Exception("Source path is not a file: {$sourcePath}");
            }

            if (!$this->isValidDestination($destinationPath)) {
                throw new Exception("Invalid destination path: {$destinationPath}");
            }

            if (File::exists($destinationPath) && !$this->force) {
                $this->skippedFiles[] = $destinationPath;
                return false;
            }

            $destinationDir = dirname($destinationPath);
            if (!File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }

            if (!File::copy($sourcePath, $destinationPath)) {
                throw new Exception("Failed to copy file");
            }

            if ($this->preservePermissions && file_exists($sourcePath)) {
                chmod($destinationPath, fileperms($sourcePath));
            }

            $this->copiedFiles[] = $destinationPath;
            return true;

        } catch (Exception $e) {
            $this->failedFiles[] = $destinationPath ?? $sourcePath;
            $this->errors[] = [
                'file' => basename($sourcePath),
                'message' => $e->getMessage(),
            ];
            return false;
        }
    }

    /**
     * Validate that the version path exists and is valid
     */
    private function validateVersionPath(string $versionPath): void
    {
        if (!File::exists($versionPath)) {
            throw new Exception("Version directory does not exist: {$versionPath}");
        }

        if (!File::isDirectory($versionPath)) {
            throw new Exception("Specified path is not a directory: {$versionPath}");
        }

        if (!File::isReadable($versionPath)) {
            throw new Exception("No read permissions for: {$versionPath}");
        }
    }

    /**
     * Get all files from the directory
     */
    private function getFiles(string $path): array
    {
        try {
            return File::allFiles($path);
        } catch (Exception $e) {
            Log::error("Error getting version files", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Process an individual file
     */
    private function processFile($file, string $versionPath, string $basePath): void
    {
        try {
            $relativePath = str_replace(
                $versionPath . DIRECTORY_SEPARATOR,
                '',
                $file->getPathname()
            );

            $destinationPath = $basePath . DIRECTORY_SEPARATOR . $relativePath;

            $this->copyFile($file->getPathname(), $destinationPath);

        } catch (Exception $e) {
            $this->failedFiles[] = $file->getPathname();
            $this->errors[] = [
                'file' => $file->getFilename(),
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validate that the destination path is safe
     */
    private function isValidDestination(string $path): bool
    {
        $basePath = base_path();
        
        $parentDir = dirname($path);
        $realPath = file_exists($parentDir) ? realpath($parentDir) : false;

        if ($realPath === false) {
            $parts = explode(DIRECTORY_SEPARATOR, $parentDir);
            while (count($parts) > 0 && $realPath === false) {
                array_pop($parts);
                $testPath = implode(DIRECTORY_SEPARATOR, $parts);
                if (file_exists($testPath)) {
                    $realPath = realpath($testPath);
                }
            }
        }

        if ($realPath === false) {
            return false;
        }

        return str_starts_with($realPath, $basePath);
    }

    /**
     * Reset statistics
     */
    private function resetStats(): void
    {
        $this->copiedFiles = [];
        $this->skippedFiles = [];
        $this->failedFiles = [];
        $this->errors = [];
    }

    /**
     * Get operation summary
     */
    public function getSummary(): array
    {
        return [
            'success' => empty($this->failedFiles),
            'copied' => count($this->copiedFiles),
            'skipped' => count($this->skippedFiles),
            'failed' => count($this->failedFiles),
            'total' => count($this->copiedFiles) + count($this->skippedFiles) + count($this->failedFiles),
            'copied_files' => $this->copiedFiles,
            'skipped_files' => $this->skippedFiles,
            'failed_files' => $this->failedFiles,
            'errors' => $this->errors,
        ];
    }

    /**
     * Get errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there were errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Log the operation
     */
    private function logOperation(string $version): void
    {
        $summary = $this->getSummary();
        
        Log::info('Version files copied', [
            'version' => $version,
            'summary' => $summary,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}