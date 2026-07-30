<?php

namespace App\Filament\Resources\SuratMasuks\Pages;

use App\Filament\Resources\SuratMasuks\SuratMasukResource;
use App\Models\DisposisiSuratMasuk;
use App\Models\Pamong;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSuratMasuk extends ViewRecord
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('tambah_disposisi')
                ->label('Tambah Disposisi')
                ->icon('heroicon-o-arrow-uturn-right')
                ->color('warning')
                ->form([
                    Select::make('id_desa_pamong')
                        ->label('Disposisi Ke (Pamong)')
                        ->options(
                            Pamong::where('pamong_status', 1)
                                ->orderBy('pamong_nama')
                                ->pluck('pamong_nama', 'pamong_id')
                        )
                        ->searchable()
                        ->required(),

                    Textarea::make('disposisi_ke')
                        ->label('Catatan Disposisi')
                        ->rows(3)
                        ->maxLength(200),
                ])
                ->action(function (array $data) {
                    DisposisiSuratMasuk::create([
                        'config_id'      => 1,
                        'id_surat_masuk' => $this->record->id,
                        'id_desa_pamong' => $data['id_desa_pamong'],
                        'disposisi_ke'   => $data['disposisi_ke'] ?? null,
                    ]);

                    Notification::make()
                        ->title('Disposisi berhasil ditambahkan')
                        ->success()
                        ->send();
                }),
        ];
    }
}
