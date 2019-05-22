<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Cart;
use App\CartItem;
use App\Http\Requests\Order\AddToCartRequest;
use App\Http\Requests\Order\CheckoutRequest;
use App\Order;
use App\OrderItem;
use App\Traits\Payments;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\CartItem as CartItemResource;
use Sdkcodes\LaraPaystack\PaystackService;
use App\Http\Resources\Cart as CartResource;
class OrderController extends Controller
{
    use Payments;

    /**
     * add to cart, create cart if not already created
     * @param AddToCartRequest $request
     * @return CartItemResource
     */
    public function addToCart(AddToCartRequest $request){
        // get data
        $validated = $request->validated();

        // get user
        $user = Auth::user();

        // create new cart or get existing
        $cart = $user->cart;

        // check if user found
        if($user == null){
            $errors = [" authenticated user not found"];
            return response(['errors'=> $errors], 404);
        }

        // check if cart is null
        if($cart == null){
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

        // add to cart items
        $cart_item = new CartItem;
        $cart_item->booking_id = $validated['booking_id'] + 0;
        $cart_item->cart_id = $cart->id;

        // save cart item
        if($cart_item->save()){
            return new CartItemResource($cart_item);
        }

        // return error if exist
        $errors = [" unknown error adding item to cart "];
        return response(['errors'=> $errors], 500);
    }

    /**
     * implement cart checkout
     * @param CheckoutRequest $request
     * @return OrderResource|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function checkout(CheckoutRequest $request){
        // get data
        $validated = $request->validated();

        // get user
        $user = Auth::user();

        // check if user found
        if($user == null){
            $errors = [" authenticated user not found"];
            return response(['errors'=> $errors], 404);
        }

        $paystackService =  new PaystackService;
        // verify transaction and checkout
        if($this->verifyTransaction($paystackService, $validated['transaction_id'], $validated['price'])){
            // create order
            $order = new Order;
            $order->user_id = $user->id;
            $order->transaction_id = $validated['transaction_id'];
            $order->price = $validated['price'];
            $order->save();

            // get cart items
            $cart = Cart::findOrFail($validated['cart_id']);
            $cart_items = $cart->items;

            // loop through and create order items from cart items
            foreach ($cart_items as $cart_item){
                // add and save order items
                $order_item  = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->booking_id = $cart_item->booking_id;
                $order_item->save();

                // save booking as paid
                $booking = Booking::findOrFail($order_item->booking_id);
                $booking->paid = true;
                $booking->save();

                // delete cart item
                $cart_item->delete();

            }
            return new OrderResource($order);

        }
        $errors = [" Transaction not valid "];
        return response(['errors'=> $errors], 406);
    }

    /**
     * get cart of current user
     * @return CartResource|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getCurrentUserCart(){
        // get user
        $user = Auth::user();

        if(is_null($user)){
            $errors = [" authenticated user not found"];
            return response(['errors'=> $errors], 404);
        }

        if(is_null($user->cart)){
            $errors = [" authenticated user has no cart"];
            return response(['errors'=> $errors], 404);
        }

        return new CartResource($user->cart);
    }
}
