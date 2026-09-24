<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function adminDashboard() {
        return view('AdminDashboard');
    }

    public function booking() {
        return view('Booking');
    }

    public function eTicket() {
        return view('ETicket');
    }

    public function landing() {
        return view('Landing');
    }

    public function login() {
        return view('Login');
    }

    public function orderHistory() {
        return view('OrderHistory');
    }

    public function payment() {
        return view('Payment');
    }

    public function profile() {
        return view('Profile');
    }

    public function register() {
        return view('Register');
    }

    public function search() {
        return view('Search');
    }

    public function seatSelection() {
        return view('SeatSelection');
    }

    public function home() {
        return view('home');
    }

    public function about() {
        return view('about');
    }

    public function service() {
        return view('service');
    }
}
