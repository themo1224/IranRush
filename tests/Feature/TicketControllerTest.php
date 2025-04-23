<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Auth\App\Models\User;
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

       // Step 3: Make a POST request to the store method in the controller
       // Using the actingAs method to authenticate the user
       $response = $this->actingAs($user)->postJson('/api/tickets/create', $data);

        // Step 4: Assert the status code of the response is 201 (created)
        $response->assertStatus(201);

        // Step 5: Assert that the response contains the correct success message
        $response->assertJson([
            'message' => 'تیکت با موفقیت ثبت شد',  // The success message that should be returned from the controller
        ]);

        $this->assertDatabaseHas('tickets', [
           'subject' => 'Test Subject',
           'description' => 'Test message content',
           'user_id' => $user->id,
        ]);
    }
}
