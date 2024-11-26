<?php

namespace SimpleEvents\Controllers;

use SimpleEvents\Core\Utils;
use SimpleEvents\Models\Event;
use SimpleEvents\Core\View;

class EventController
{
    /**
     * Render the home page.
     *
     * @return void
     */
    public function index(): void
    {
        View::render('pages/index.php');
    }

    /**
     * List all events and render them.
     *
     * @return void
     */
    public function archive(): void
    {
        /** @var array $events Array of events */
        $events = Event::query();
        View::render('pages/events.php', [ 'events' => $events ]);
    }

    /**
     * Handle event creation from form submission.
     *
     * Expects POST data with the following keys:
     * - _team1_id: int
     * - _team2_id: int
     * - _venue_id: int
     * - event_date: string (format: 'Y-m-d H:i:s')
     * - description: string (optional)
     *
     * @return void
     */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                '_team1_id'   => $_POST['_team1_id'],
                '_team2_id'   => $_POST['_team2_id'],
                '_venue_id'   => $_POST['_venue_id'],
                'event_date'  => $_POST['event_date'],
                'description' => $_POST['description'],
            ];

            Event::create($data);
            Utils::redirect('/events');
        }
    }

    /**
     * Get all sports and return them as a JSON response.
     *
     * @return void
     */
    public function sports(): void
    {
        $sports = Event::getSports();
        Utils::jsonResponse($sports);
    }

    /**
     * Get all teams for a given sport and return them as a JSON response.
     *
     * Expects GET parameter:
     * - _sport_id: int (ID of the sport to filter teams)
     *
     * @return void
     */
    public function teams(): void
    {
        /** @var int $sportId ID of the sport */
        $sportId = $_GET['_sport_id'] ?? null;

        if (! $sportId) {
            Utils::jsonResponse([ 'error' => 'Missing required parameter: _sport_id' ], 400);

            return;
        }

        $teams = Event::getTeams($sportId);
        Utils::jsonResponse($teams);
    }

    /**
     * Get all venues and return them as a JSON response.
     *
     * @return void
     */
    public function venues(): void
    {
        $venues = Event::getVenues();
        Utils::jsonResponse($venues);
    }
}
