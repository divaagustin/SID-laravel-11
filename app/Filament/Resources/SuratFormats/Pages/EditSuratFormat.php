<?php

namespace App\Filament\Resources\SuratFormats\Pages;

use App\Filament\Resources\SuratFormats\SuratFormatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuratFormat extends EditRecord
{
    protected static string $resource = SuratFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! empty($data['form_isian'])) {
            $parsed = is_string($data['form_isian']) ? json_decode($data['form_isian'], true) : $data['form_isian'];
            if (is_array($parsed)) {
                $visual = [];
                foreach ($parsed as $key => $val) {
                    $cleanKey = ucwords(str_replace(['_', '-'], ' ', (string) $key));
                    if (is_array($val)) {
                        $items = [];
                        array_walk_recursive($val, function($v) use (&$items) {
                            if ($v !== '' && $v !== null) {
                                $items[] = (string) $v;
                            }
                        });
                        $visual[$cleanKey] = !empty($items) ? implode(', ', $items) : 'Biasa / Default';
                    } else {
                        $visual[$cleanKey] = (string) $val;
                    }
                }
                $data['form_isian_visual'] = $visual;
            }
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('form_isian_visual', $data)) {
            $data['form_isian'] = ! empty($data['form_isian_visual']) ? json_encode($data['form_isian_visual']) : null;
            unset($data['form_isian_visual']);
        }
        return $data;
    }
}
