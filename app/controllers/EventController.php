<?php

namespace SimpleEvents\Controllers;

use SimpleEvents\Models\Event;
use SimpleEvents\Core\View;

class EventController
{
    /**
     * Render the home page
     */
    public function index(): void
    {
        View::render('pages/index.php');
    }

    /**
     * List all events
     *
     * @return void
     */
    public function archive(): void
    {
        /** @var Event[] $events */
        $events = Event::query();
        View::render('pages/events.php', ['events' => $events]);
    }

    /**
     * Handle event creation
     */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                '_team1_id' => htmlspecialchars($_POST['_team1_id']),
                '_team2_id' => htmlspecialchars($_POST['_team2_id']),
                '_venue_id' => htmlspecialchars($_POST['_venue_id']),
                'event_date' => htmlspecialchars($_POST['event_date']),
                'description' => htmlspecialchars($_POST['description']),
            ];

            Event::create($data);
            header('Location: /events');
            exit;
        }
    }
}
