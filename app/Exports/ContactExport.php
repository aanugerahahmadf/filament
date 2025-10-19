<?php

namespace App\Exports;

use App\Models\Contact;

class ContactExport extends BaseExport
{
    /**
     * Get the headings for the export
     */
    protected function getHeadings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'WhatsApp',
            'Address',
            'Instagram',
            'Facebook',
            'LinkedIn',
            'Position',
            'Department',
            'Phone',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map a row to an array for export
     *
     * @param  Contact  $contact
     */
    protected function mapRow($contact): array
    {
        return [
            $contact->id,
            $contact->name,
            $contact->email,
            $contact->whatsapp,
            $contact->address,
            $contact->instagram,
            $contact->facebook,
            $contact->linkedin,
            $contact->position,
            $contact->department,
            $contact->phone,
            $contact->created_at ? $contact->created_at->format('Y-m-d H:i:s') : '',
            $contact->updated_at ? $contact->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
