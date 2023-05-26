<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\FrontBanner;
use App\Models\Organization;


class HomepageController extends Controller
{
    public static function getEventLowestPrice($eventsRaw) {
        $events = [];
        foreach ($eventsRaw as $e) {
            $cheapestTicketFound = null;
            foreach ($e->sessions as $session) {
                if ($session->cheapest_ticket != null && count($session->cheapest_ticket) > 0) {
                    if ($cheapestTicketFound == null) {
                        $cheapestTicketFound = $session->cheapest_ticket[0]->price;
                    } else {
                        if ($cheapestTicketFound > $session->cheapest_ticket[0]->price) {
                            $cheapestTicketFound = $session->cheapest_ticket[0]->price;
                        }
                    }
                }
            }
            $e->price = $cheapestTicketFound;
            array_push($events, $e);
        }
        return $events;
    }

    public function featuredEvent(Request $request)
    {
        $now = Carbon::now();
        $eventsFeatured = Event::where([['is_publish', 1], ['end_date', '>=', $now->format('Y-m-d')]])->orderBy('featured', 'DESC')
        ->take(9)->orderBy('created_at', 'DESC')->get();
        $eventsFeatured = self::getEventLowestPrice($eventsFeatured);

        return response()->json([
            'status' => 200,
            'events' => $eventsFeatured
        ]);
    }

    public function recomendEvent(Request $request)
    {
        $now = Carbon::now();
        $eventsRaw = Event::where([
            ['is_publish', 1],
            ['start_date', '>=', $now->format('Y-m-d')]
        ])->with(['organizer', 'sessions.cheapestTicket'])
        ->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($eventsRaw);

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }

    public function categoryList(Request $request)
    {
        $categories = config('event_types');

        return response()->json([
            'status' => 200,
            'categorys' => $categories
        ]);
    }

    public function topicList(Request $request)
    {
        $topics = config('agendakota')['event_topics'];

        return response()->json([
            'status' => 200,
            'topics' => $topics
        ]);
    }

    public function cityList(Request $request)
    {
        $cities = City::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->take(5)->get();

        return response()->json([
            'status' => 200,
            'cities' => $cities
        ]);
    }

    public function cityFilter(Request $request)
    {
        $now = Carbon::now();
        $events = Event::where([
            ['is_publish', 1],
            ['city', 'LIKE', '%'.$request->city.'%'],
            ['start_date', '>=', $now->format('Y-m-d')]
        ])
        ->with(['organizer', 'sessions.cheapestTicket'])
        ->orderBy('created_at', 'DESC')->get();
        $events = self::getEventLowestPrice($events);

        return response()->json([
            'status' => 200,
            'events' => $events,
        ]);
    }

    public function timeFilter(Request $request)
    {
        $filter = [['is_publish', 1]];
        $now = Carbon::now();
        $eventQuery = Event::where($filter);

        if ($request->timeframe == "Minggu Ini") {
            $eventQuery = $eventQuery->whereBetween('start_date', [
                $now->startOfWeek()->format('Y-m-d'),
                $now->endOfWeek()->format('Y-m-d')
            ])->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "Minggu Depan") {
            $nextWeek = $now->endOfWeek()->addDay();
            $startOfNextWeek = $nextWeek->format('Y-m-d');
            $endOfNextWeek = $nextWeek->endOfWeek()->format('Y-m-d');
            
            $eventQuery = $eventQuery->whereBetween('start_date', [$startOfNextWeek, $endOfNextWeek])
            ->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "Bulan Ini") {
            $eventQuery = $eventQuery->whereBetween('start_date', [
                $now->startOfMonth()->format('Y-m-d'),
                $now->endOfMonth()->format('Y-m-d')
            ])->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "Bulan Depan") {
            $nextMonth = $now->endOfMonth()->addDay();
            $startOfNextMonth = $nextMonth->format('Y-m-d');
            $endOfNextMonth = $nextMonth->endOfMonth()->format('Y-m-d');

            $eventQuery = $eventQuery->whereBetween('start_date', [$startOfNextMonth, $endOfNextMonth])
            ->where('start_date', '<=', $now->format('Y-m-d'));
        } else if ($request->timeframe == "Hari Ini") {
            $eventQuery = $eventQuery->where('start_date', $now->format('Y-m-d'));
        } else if ($request->timeframe == "Besok") {
            $tomorrow = $now->addDay()->format('Y-m-d');
            $eventQuery = $eventQuery->where('start_date', $tomorrow);
        }

        $events = $eventQuery->with(['organizer', 'sessions.cheapestTicket'])->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => 200,
            'events' => $events,
        ]);
    }

    public function bannerList(Request $request)
    {
        $bannerImages = FrontBanner::orderBy('priority','DESC')->get();

        return response()->json([
            'status' => 200,
            'banners' => $bannerImages
        ]);
    }

    public function favoriteOrganizer(Request $request) {
        $organizers = Organization::withCount('events')->orderBy('events_count', 'DESC')
        ->get();
        
        return response()->json([
            'status' => 200,
            'organizers' => $organizers
        ]);
    }
}