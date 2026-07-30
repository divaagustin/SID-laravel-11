<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\IdentitasDesa;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class IdentitasDesaPage extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-building-library';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Info Desa';
    }

    public static function getNavigationLabel(): string
    {
        return 'Identitas Desa';
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Identitas & Profil Desa';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    protected string $view = 'filament.pages.identitas-desa-page';

    public function mount(): void
    {
        $identitas = IdentitasDesa::find(1);
        if ($identitas) {
            $this->form->fill($identitas->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Informasi Administrasi Desa')
                    ->description('Data resmi administrasi kewilayahan desa.')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_desa')->label('Nama Desa')->required(),
                            TextInput::make('kode_desa')->label('Kode Desa (Kemendagri)')->required(),
                            TextInput::make('nama_kecamatan')->label('Kecamatan')->required(),
                            TextInput::make('nama_kabupaten')->label('Kabupaten/Kota')->required(),
                            TextInput::make('nama_propinsi')->label('Provinsi')->required(),
                            TextInput::make('kode_pos')->label('Kode Pos')->numeric(),
                        ])
                    ]),

                Section::make('Kontak & Alamat Kantor')
                    ->description('Informasi alamat, telepon, dan email kantor desa.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('alamat_kantor')->label('Alamat Kantor Desa')->columnSpanFull(),
                            TextInput::make('email_desa')->label('Email Resmi Desa')->email(),
                            TextInput::make('telepon')->label('Telepon Kantor'),
                            TextInput::make('website')->label('Website Resmi')->url()->placeholder('https://'),
                        ])
                    ]),

                Section::make('Logo & Identitas Visual')
                    ->description('Upload logo resmi desa yang akan ditampilkan di portal publik.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Desa')
                            ->image()
                            ->disk('public')
                            ->directory('logo-desa')
                            ->maxSize(2048)
                            ->helperText('Format: JPG, PNG, SVG. Ukuran maks: 2MB.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Sejarah Desa')
                    ->description('Narasi sejarah berdiri dan perkembangan desa. Ditampilkan di halaman Tentang Desa portal publik.')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        RichEditor::make('sejarah_desa')
                            ->label('Sejarah Desa')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'bulletList', 'orderedList',
                                'blockquote', 'link',
                                'undo', 'redo',
                            ])
                            ->placeholder('Tuliskan narasi sejarah desa dari awal berdiri hingga perkembangan saat ini...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Visi & Misi Desa')
                    ->description('Visi dan Misi pemerintahan desa yang ditampilkan di halaman Tentang Desa.')
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('visi')
                                ->label('Pernyataan VISI')
                                ->placeholder('Contoh: Terwujudnya Desa Serdang yang Maju, Sejahtera, dan Berbudaya...')
                                ->columnSpanFull(),
                            RichEditor::make('misi')
                                ->label('Butir-Butir MISI')
                                ->toolbarButtons([
                                    'bold', 'italic',
                                    'bulletList', 'orderedList',
                                    'undo', 'redo',
                                ])
                                ->placeholder("Tulis setiap butir misi:\n1. Meningkatkan pelayanan publik yang prima...")
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Koordinat & Peta Desa')
                    ->description('Titik koordinat kantor desa dan batas wilayah untuk ditampilkan di peta portal publik.')
                    ->icon('heroicon-o-map')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('lat')
                                ->label('Latitude (Lintang)')
                                ->placeholder('Contoh: 2.9167')
                                ->numeric(),
                            TextInput::make('lng')
                                ->label('Longitude (Bujur)')
                                ->placeholder('Contoh: 99.6667')
                                ->numeric(),
                            TextInput::make('zoom')
                                ->label('Zoom Level Peta')
                                ->numeric()
                                ->default(14)
                                ->minValue(1)
                                ->maxValue(20),
                        ]),
                        Textarea::make('path')
                            ->label('Path Batas Wilayah Desa (Format LatLng JSON)')
                            ->placeholder('[[lat1,lng1],[lat2,lng2],...]  — diisi otomatis jika digambar di peta GIS')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $identitas = IdentitasDesa::find(1);
        if ($identitas) {
            $identitas->update($data);
        } else {
            $data['id'] = 1;
            IdentitasDesa::create($data);
        }

        \Illuminate\Support\Facades\Cache::forget('global_config_brand');
        \Illuminate\Support\Facades\Cache::forget('global_config_desa');
        \Illuminate\Support\Facades\Cache::forget('portal_identitas_desa');

        Notification::make()
            ->title('Data identitas desa berhasil disimpan')
            ->success()
            ->send();
    }
}
