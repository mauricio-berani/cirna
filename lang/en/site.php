<?php

return [
    'sectors' => [
        'general'    => 'General',
        'sales'      => 'Sales',
        'purchasing' => 'Purchasing',
        'quality'    => 'Quality',
        'finance'    => 'Finance',
        'tooling'    => 'Tooling',
    ],

    'contact' => [
        'fields' => [
            'name'    => 'Name',
            'email'   => 'E-mail',
            'phone'   => 'Phone',
            'sector'  => 'Area of interest',
            'message' => 'Message',
        ],
        'submit' => 'Send message',
        'feedback' => [
            'success'  => 'Message sent successfully! We will contact you shortly.',
            'error'    => 'We could not send your message. Please try again later.',
            'throttle' => 'Too many attempts. Please wait :seconds seconds before trying again.',
        ],
        'email' => [
            'subject' => 'New contact from the website — :sector',
            'heading' => 'New contact message',
            'reply'   => 'Reply to customer',
        ],
    ],

    'quality' => [
        'view_certificate' => 'View ISO certificate',
    ],

    'careers' => [
        'submit'      => 'Send application',
        'resume_hint' => 'PDF only, maximum size 5 MB.',
        'resume_pdf'  => 'Resume in PDF attached by the candidate.',
        'uploading'   => 'Uploading file...',
        'areas' => [
            'production'     => 'Production',
            'tooling'        => 'Tooling',
            'quality'        => 'Quality',
            'administrative' => 'Administrative',
            'commercial'     => 'Sales',
            'other'          => 'Other area',
        ],
        'feedback' => [
            'success'     => 'Application sent successfully! Thank you for your interest.',
            'error'       => 'We could not send your application. Please try again later.',
            'throttle'    => 'Too many attempts. Please wait :seconds seconds before trying again.',
            'invalid_pdf' => 'The uploaded file is not a valid PDF.',
        ],
        'email' => [
            'subject'         => 'New application — :name',
            'heading'         => 'New application received from the website',
            'reply'           => 'Reply to candidate',
            'attachment_note' => 'The candidate resume is attached to this email as a PDF.',
        ],
    ],
];
