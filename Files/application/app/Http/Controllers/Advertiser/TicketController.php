<?php

namespace App\Http\Controllers\Advertiser;

use App\Traits\SupportTicketManager;
use App\Http\Controllers\Controller;
class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->layout = 'frontend';

        $this->middleware(function ($request, $next) {
            $this->user = authAdvertiser();
            if ($this->user) {
                $this->layout = 'master';
            }
            return $next($request);
        });

        $this->redirectLink = 'advertiser.ticket.view';
        $this->userType     = 'advertiser';
        $this->column       = 'advertiser_id';
    }
}
