<?php

namespace App\Filament\Resources\Contacts\Widgets;

use App\Models\Contact;
use Filament\Widgets\ChartWidget;

class ContactStatsChart extends ChartWidget
{
    protected ?string $heading = 'Contact Information Overview';

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get contact statistics
        $totalContacts = Contact::count();
        $contactsWithEmail = Contact::whereNotNull('email')->count();
        $contactsWithPhone = Contact::whereNotNull('whatsapp')->count();
        $contactsWithSocial = Contact::whereNotNull('instagram')->count();

        return [
            'labels' => ['Total Contacts', 'With Email', 'With Phone', 'With Social'],
            'datasets' => [
                [
                    'label' => 'Contact Statistics',
                    'data' => [$totalContacts, $contactsWithEmail, $contactsWithPhone, $contactsWithSocial],
                    'backgroundColor' => ['#10B981', '#8B5CF6', '#06B6D4', '#F59E0B'],
                    'borderColor' => ['#10B981', '#8B5CF6', '#06B6D4', '#F59E0B'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
