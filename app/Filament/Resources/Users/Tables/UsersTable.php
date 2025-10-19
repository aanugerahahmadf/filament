<?php

namespace App\Filament\Resources\Users\Tables;

use App\Exports\UserExport;
use App\Models\User;
use Filament\Actions\Action as HeaderAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('password')
                    ->label('Password')
                    ->formatStateUsing(fn () => '••••••••')
                    ->searchable(false),
                TextColumn::make('place_of_birth')
                    ->label('Tempat Lahir')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label('No. Handphone / WhatsApp')
                    ->searchable(),
                TextColumn::make('phone_verified')
                    ->label('Phone Verified')
                    ->formatStateUsing(fn (User $record): string => $record->phone_verified ? 'Yes' : 'No')
                    ->color(fn (User $record): string => $record->phone_verified ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('phone_verification_method')
                    ->label('Verification Method')
                    ->formatStateUsing(fn (User $record): string => $record->phone_verification_method ? ucfirst($record->phone_verification_method) : 'Not Verified')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('avatar')
                    ->searchable(),
                TextColumn::make('last_seen_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('two_factor_confirmed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->color('info')
                    ->size('lg'),
                EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->size('lg'),
                DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->size('lg'),
                HeaderAction::make('verifyPhone')
                    ->label('Verify Phone')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->visible(fn (User $record): bool => !empty($record->phone_number) && !$record->phone_verified)
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        // In a real implementation, you would send an OTP to the user
                        // For now, we'll just mark the phone as verified
                        $record->markPhoneAsVerified();
                    }),
                HeaderAction::make('sendSmsOtp')
                    ->label('Send SMS OTP')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->visible(fn (User $record): bool => !empty($record->phone_number) && !$record->phone_verified)
                    ->action(function (User $record) {
                        // Send SMS OTP
                        $record->sendSmsOtp();
                    }),
                HeaderAction::make('sendWhatsAppOtp')
                    ->label('Send WhatsApp OTP')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->color('success')
                    ->visible(fn (User $record): bool => !empty($record->phone_number) && !$record->phone_verified)
                    ->action(function (User $record) {
                        // Send WhatsApp OTP
                        $record->sendWhatsAppOtp();
                    }),
            ])
            ->toolbarActions([
                HeaderAction::make('export')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $users = \App\Models\User::all();

                        return Excel::download(new UserExport($users), 'users-'.now()->format('Y-m-d-H-i-s').'.xlsx');
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
