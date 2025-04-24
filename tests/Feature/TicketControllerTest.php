<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\App\Models\User;
use Modules\Ticket\App\Events\TicketCreated;
use Modules\Ticket\App\Providers\TicketServiceProvider;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Load the module's service provider if needed
    //     $this->app->register(TicketServiceProvider::class);
    // }

    use RefreshDatabase;

    public function test_create_ticket()
    {
       // Step 1: Create a user for authentication
       $user = User::factory()->create();

       // Step 2: Prepare the data for the new ticket
       $data = [
           'subject' => 'Test Subject',  // Ticket's subject
           'description' => 'Test message content',  // Ticket's description/message
           'attachment' => null  // No attachment in this case
       ];

        // Step 3: Fake Event, Notification, and Email to intercept them
       Event::fake();
       Notification::fake();
       Mail::fake();

       // Step 4: Make a POST request to the store method in the controller
       // Using the actingAs method to authenticate the user
       $response = $this->actingAs($user)->postJson('/api/tickets/create', $data);

        // Step 5: Assert the status code of the response is 201 (created)
        $response->assertStatus(201);

        // Step 6: Assert that the response contains the correct success message
        $response->assertJson([
            'message' => 'تیکت با موفقیت ثبت شد',  // The success message that should be returned from the controller
        ]);

        // Step 7: Assert that the ticket is saved in the database

        $this->assertDatabaseHas('tickets', [
           'subject' => 'Test Subject',
           'description' => 'Test message content',
           'user_id' => $user->id,
        ]);

        //step 8: Assert that the TicketCreated event was dispatched
        Event::assertDispatched(TicketCreated::class);

        //step 9: Assert that the notification was sent to the user
        Notification::assertSentTo($user, )
    }

      /**
     * Test ticket creation with event, notification, and email.
     *
     * @return void
     */
    
}
