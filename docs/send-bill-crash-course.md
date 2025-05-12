# 🎉 Filament "Send Bill" Crash Course 📧🚀

Welcome, Fullstack Adventurers! Strap in as we journey through the epic saga of adding a "Send Bill" button to your Filament admin and firing off that sweet, sweet Laravel mail. Expect dev humor, code, and a sprinkle of Laravel Zen. 🧙‍♂️

---

## 1. The Mission

**Goal:** On the patient edit page, add a Filament action button that triggers a plain `Mail::send` call, composes an HTML bill in the email body (no attachments!), and pops a Filament notification.

Why? Because real heroes don’t send PDFs—they send HTML tables. 💅

---

## 2. The Action Button

File: `app/Filament/Resources/PatientResource/Pages/EditPatient.php`

```php
// ...existing code...
use Filament\Actions;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class EditPatient extends EditRecord
{
    // ...existing code...

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
        $patient    = $this->record;
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
``` 

> Note: We removed the nonexistent `$this->notify()` call and replaced it with Filament’s `Notification::make()`. 💡

---

## 3. The Blade View

File: `resources/views/emails/bill.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill for {{ $patient->name }}</title>
</head>
<body>
    <h1>Bill for {{ $patient->name }}</h1>
    <p>Dear {{ $patient->owner->name }},</p>
    <p>Here is the bill for your pet <strong>{{ $patient->name }}</strong>:</p>

    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border:1px solid #000; padding:5px; text-align:left;">Description</th>
                <th style="border:1px solid #000; padding:5px; text-align:right;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $treatment)
                <tr>
                    <td style="border:1px solid #000; padding:5px;">{{ $treatment->description }}</td>
                    <td style="border:1px solid #000; padding:5px; text-align:right;">€{{ number_format($treatment->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php $total = $treatments->sum('price'); @endphp
    <p><strong>Total: €{{ number_format($total, 2) }}</strong></p>
    <p>Thank you for your business.</p>
</body>
</html>
``` 

---

## 4. Do’s & Don’ts 📝

**Do:**
- ✅ Use Filament’s prebuilt actions for a consistent UI.
- ✅ Leverage Blade for HTML email templates—no need for attachments! 😉
- ✅ Fire off quick notifications with `Notification::make()`.

**Don’t:**
- ❌ Don’t try to use `$this->notify()`—it doesn’t exist in this context. 🚫
- ❌ Avoid overcomplicating with Mailables/Markdown mail until you need them.
- ❌ Never send bills in plain text—your clients deserve pretty tables.

---

## 5. Laravel Wisdom 🌱

> “Simplicity is the ultimate sophistication.” — *Filament & Laravel* 

- **Facade power:** `Mail::send()` gets you instant gratification. But when your emails grow complex, switch to Mailables!
- **Eloquent relations:** `$patient->treatments` is your friend—trust it to fetch data.
- **Notifications:** Filament Notifications are lightweight and perfect for admin UIs.

---

Congrats—you just added a killer feature in record time! 🎉

Now go forth, code more, refactor less, and may your SQL always be optimized! ⚡️
