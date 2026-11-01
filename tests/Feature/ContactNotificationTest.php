<?php

use App\Mail\SubmissionReceived;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Mail;

function contactPayload(array $overrides = []): array
{
    return [
        'name' => "Rowan O'Brien",
        'email' => 'rowan@example.com',
        'subject' => 'A question about prints',
        'message' => "Do you sell prints of the dragon series?\nI'd like two.",
        ...$overrides,
    ];
}

beforeEach(function () {
    config(['mail.owner_address' => 'owner@example.com']);
    Mail::fake();
});

test('a contact message queues one email to the owner with the full message', function () {
    $this->post(route('contact.store'), contactPayload())
        ->assertSessionHas('success');

    Mail::assertQueuedCount(1);
    Mail::assertQueued(SubmissionReceived::class, function (SubmissionReceived $mail) {
        $mail->assertHasTo('owner@example.com');
        $mail->assertHasReplyTo('rowan@example.com', "Rowan O'Brien");
        $mail->assertHasSubject('New message: A question about prints');
        $mail->assertSeeInText("Rowan O'Brien");
        $mail->assertSeeInText('rowan@example.com');
        $mail->assertSeeInText("Do you sell prints of the dragon series?\nI'd like two.");

        return true;
    });
});

test('a commission request email includes the budget range and desired due date', function () {
    $this->post(route('contact.store'), contactPayload([
        'type' => 'commission',
        'budget_range' => '$200 to $400',
        'desired_due_date' => '2031-03-09',
    ]));

    Mail::assertQueuedCount(1);
    Mail::assertQueued(SubmissionReceived::class, function (SubmissionReceived $mail) {
        $mail->assertHasTo('owner@example.com');
        $mail->assertHasReplyTo('rowan@example.com', "Rowan O'Brien");
        $mail->assertHasSubject("New commission request from Rowan O'Brien");
        $mail->assertSeeInText('Do you sell prints of the dragon series?');
        $mail->assertSeeInText('Budget Range: $200 to $400');
        $mail->assertSeeInText('Desired Due Date: March 9, 2031');

        return true;
    });
});

test('a commission request email leaves out details that were not given', function () {
    $this->post(route('contact.store'), contactPayload(['type' => 'commission']));

    Mail::assertQueued(SubmissionReceived::class, function (SubmissionReceived $mail) {
        $mail->assertDontSeeInText('Budget Range');
        $mail->assertDontSeeInText('Desired Due Date');

        return true;
    });
});

test('nothing is sent and the submission succeeds when the destination is unset', function (?string $destination) {
    config(['mail.owner_address' => $destination]);

    $this->post(route('contact.store'), contactPayload())
        ->assertSessionHas('success');

    Mail::assertNothingOutgoing();
    $this->assertDatabaseHas('contact_messages', ['email' => 'rowan@example.com']);
})->with([null, '']);

test('the submission is saved and the failure reported when the email cannot be queued', function () {
    Exceptions::fake();
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Queue unavailable'));

    $this->post(route('contact.store'), contactPayload(['type' => 'commission']))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', ['email' => 'rowan@example.com']);
    $this->assertDatabaseCount('commission_requests', 1);
    Exceptions::assertReported(RuntimeException::class);
});

test('a general message email has no commission details', function () {
    $this->post(route('contact.store'), contactPayload());

    Mail::assertQueued(SubmissionReceived::class, function (SubmissionReceived $mail) {
        $mail->assertDontSeeInText('Budget Range');

        return true;
    });
});
