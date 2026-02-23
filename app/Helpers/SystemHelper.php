<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class SystemHelper
{
    public static function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $index => $line) {
            // Ignore comments and empty lines
            if (Str::startsWith(trim($line), '#') || empty(trim($line))) {
                continue;
            }

            // Use preg_match for more robust analysis
            if (preg_match('/^' . preg_quote($key) . '\s*=\s*(.*)$/', $line, $matches)) {
                $currentValue = $matches[1];

                // Handling Quotation Marks
                if (Str::startsWith($currentValue, '"') && Str::endsWith($currentValue, '"')) {
                    $value = '"' . $value . '"';
                }
                $lines[$index] = $key . '=' . $value;
                break;
            }
        }

        $newContent = implode("\n", $lines) . "\n";

        file_put_contents($path, $newContent);

        Artisan::call('config:clear');

        return true;
    }
}
