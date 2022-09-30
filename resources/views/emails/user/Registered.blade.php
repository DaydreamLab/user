@include('emails.Components.Body', [
    'subject' => $subject,
    'content' => $content,
    'user'    => $user,
    'clickType' => $clickType,
    'clickUrl' => $clickUrl
])