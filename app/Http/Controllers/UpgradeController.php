<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use App\Services\Upgrade\LanguageStrings;

final class UpgradeController extends Controller
{
	private const VERSION = '1.0';

	public function execute()
	{
		if (!auth()->user()->isSuperAdmin()) {
			return redirect(route('home'));
		}

		if (Setting::get('version') === self::VERSION) {
			return redirect()->route('filament.admin.pages.dashboard');
		}

		try {
			//addStringsToMultipleFiles(LanguageStrings::get(), ['en', 'es', 'pt-BR']);

			// Execute migration
			Artisan::call('down');
			Artisan::call('migrate', ['--force' => true]);
			Artisan::call('up');

			// Clear Cache, Config and Views
			Artisan::call('optimize:clear');
			Artisan::call('queue:restart');
		} catch (\Exception $e) {
			Artisan::call('up');

			Notification::make()
				->title($e->getMessage())
				->danger()
				->send();

			return redirect()->route('filament.admin.pages.dashboard');
		}

		Notification::make()
			->title('Upgrade completed successfully.')
			->success()
			->send();

		return redirect()->route('filament.admin.pages.dashboard');
	}
}
