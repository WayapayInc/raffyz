<?php

declare(strict_types=1);

namespace App\Services\Upgrade;

final class LanguageStrings
{
	public static function get(): array
	{
		return [
			'messages.php' => [
				'gift_to_creator' => [
					'en' => 'Gift to creator',
					'es' => 'Regalo a creador',
					'pt-BR' => 'Doar para o criador',
				],
			],
		];
	}
}
