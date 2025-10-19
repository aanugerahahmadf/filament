<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\CctvOperationalTable;
use App\Filament\Widgets\CctvStatusChart;
use App\Filament\Widgets\CctvStatusTrendChart;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\OfflineAlerts;
use App\Filament\Widgets\StreamingPerformanceChart;
use App\Filament\Widgets\UserActivityChart;
use App\Http\Middleware\EnsureSuperAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login('App\\Filament\\Pages\\Auth\\Login')
            ->registration('App\\Filament\\Pages\\Auth\\Register')
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->profile()
            ->brandName('Kilang Pertamina Internasional')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DashboardStats::class,
                CctvStatusChart::class,
                CctvOperationalTable::class,
                OfflineAlerts::class,
                CctvStatusTrendChart::class,
                UserActivityChart::class,
                StreamingPerformanceChart::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('User Interface')
                     ->icon('bxs-user-detail'),
                NavigationGroup::make()
                    ->label('Location And Maps')
                    ->icon('bxs-map-pin'),
                NavigationGroup::make()
                    ->label('Communication')
                    ->icon('bxs-message-detail'),
                NavigationGroup::make()
                    ->label('Account')
                    ->icon('bxs-user-account'),

            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('5s')
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureSuperAdmin::class,
            ])
            ->plugin(
                FilamentEditProfilePlugin::make()
                    ->setTitle('Profile')
                    ->setNavigationLabel('Profile')
                    ->setNavigationGroup('Account')
                    ->setSort(10)
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:51200' //only accept jpeg and png files with a maximum size of 50MB (51200 KB)
                    )
            )
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn(): string => Auth::user()?->name ?? 'Profile')
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->visible(function (): bool {
                        return Auth::check();
                    }),
            ]);
    }
}
