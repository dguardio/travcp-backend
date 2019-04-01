<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use App\Experience;
use Tests\TestCase;
use App\BookableType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    

    public function testCanBookExperience(){
        // $user = User::firstOrFail();
        // $bookableType = BookableType::whereName('experience')->first();
        // $experience = Experience::inRandomOrder()->first();
        // $response = $this->actingAs($user)
        //     ->json('POST', '/api/bookings/experiences/'. $experience->id,  [
        //         'bookable_id' => $experience->id,
        //         'merchant_id' => 1,
        //         'price' => $experience->naira_price,
        //         'currency' => "Naira",
        //         'date' => Carbon::today()->addDays(2),
        //     ]);
        $data = ['start_date' => Carbon::tomorrow()->toDateString(), 'end_date' => Carbon::today()->addDays(\rand(4, 12))->toDateString()];
        $response = $this->makeBooking('experience', $data);
        $response->assertSee('success');
    }

    public function testCanBookEvent(){
        $response = $this->makeBooking('event');
        $response->assertSee('success');
    }

    // public function testCanBookMenu(){
    //     $response = $this->makeBooking('menu');
    //     $response->assertSee('success');
    // }

    private function makeBooking($bookingType, $payload=null){
        $bookingTypeClass = \ucfirst($bookingType);
        $bookingTypeClass = "\App\\".$bookingTypeClass;
        $user = User::inRandomOrder()->first();
        $bookableType = BookableType::whereName($bookingType)->firstOrFail();
        $bookable = $bookingTypeClass::inRandomOrder()->firstOrFail();
        $data = [
                'bookable_id' => $bookable->id,
                'price' => $bookable->naira_price,
                'currency' => "Naira"
        ];
        if (\is_array($payload)){
            $data = \array_merge($data, $payload);
        }
        $response = $this->actingAs($user)
            ->json('POST', '/api/bookings/'.$bookingType."s/".$bookable->id, $data);
        return $response;
    }
}
