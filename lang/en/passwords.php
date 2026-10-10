<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | Published from the framework so 'token' can be reworded. The broker
    | returns INVALID_TOKEN for a token that is malformed AND for one that has
    | simply run out - 60 minutes, per config/auth.php - and there is no way to
    | tell the two apart from its return value. Laravel's "invalid" reads as
    | "you copied it wrong", so an invitee whose link expired retries the same
    | dead link instead of asking for a new one. The wording below covers both
    | cases and says what to do next.
    |
    */

    'reset' => 'Your password has been reset.',
    'sent' => 'We have emailed your password reset link.',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This link is no longer valid - it may have expired, or already been used. Ask an administrator to send you a new invitation.',
    'user' => "We can't find a user with that email address.",

];
