<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the message library to build
    | the simple message links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
    'messages' => [
        'create' => [
            'success' => 'New :text has been saved successfully!',
            'error' => 'Failed to save new :text.',
        ],
        'update' => [
            'success' => ':text has been successfully updated!',
            'error' => 'Failed to update :text.',
        ],
        'delete' => [
            'success' => ':text has been successfully deleted!',
            'error' => 'Failed to delete :text.',
        ],
        'request' => [
            'success' => 'Request processed successfully.',
            'error' => 'Whoops! Something went wrong!',
        ],
        'empty' => [
            'data' => 'No data available.',
            'value' => 'No :text specified.',
            'id' => ':text :id does not exist',
        ],
        'comment' => [
            'success' => 'New comment has been successfully added.',
            'error' => 'Whoops! Failed to add a new comment.',
        ],
        'seeder' => [
            'cancelled' => "\e[33m Cancelled. \e[31mTable :table is not empty.\n",
        ],
        'suspend' => [
            'success' => ':text has been suspended!',
            'error' => 'Failed to suspend :text.',
        ],
        'recommend' => [
            'success' => ':text has been recommended!',
            'error' => 'Failed to recommend :text.',
        ],
        'approve' => [
            'success' => ':text has been approved!',
            'error' => 'Failed to approve :text.',
        ],
    ],
    'error' => [
        '401' => 'Unauthorized access.',
        '403' => 'Forbidden access.',
        '404' => 'Page not found.',
        '500' => 'Whoops! Something went wrong.',
    ],
];
