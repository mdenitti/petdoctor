<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('sendBill')
                ->label('Send Bill')
                ->color('primary')
                ->icon('heroicon-o-user-group')
                ->requiresConfirmation()
                ->action('sendBill'),
        ];
    }

    public function sendBill(): void
    {
        $patient = $this->record;
        $treatments = $patient->treatments;

        Mail::send('emails.bill', compact('patient', 'treatments'), function ($message) use ($patient) {
            $message->to($patient->owner->email)
                    ->subject('Bill for ' . $patient->name);
        });

        Notification::make()
            ->title('Bill email sent!')
            ->success()
            ->send();
    }
}
