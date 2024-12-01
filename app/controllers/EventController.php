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
        $args = [];

        // Check if _sport_id is set and validate it
        if (isset($_GET['_sport_id']) && is_numeric($_GET['_sport_id'])) {
            $args['_sport_id'] = (int)$_GET['_sport_id'];
        }

        // Check if date is set and validate it
        if (isset($_GET['date']) && \DateTime::createFromFormat('Y-m-d', $_GET['date'])) {
            $args['date'] = $_GET['date'];
        }

        $events = Event::query($args);
        View::render('pages/index.php', [ 'events' => $events ]);
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                'status' => 'error',
                'message' => 'Method not allowed.',
            ]);
            return;
        }

        // Parse and validate input
        $data = [
            '_team1_id'   => $_POST['_team1_id'] ?? null,
            '_team2_id'   => $_POST['_team2_id'] ?? null,
            '_venue_id'   => $_POST['_venue_id'] ?? null,
            'event_date'  => $_POST['event_date'] ?? null,
            'description' => $_POST['description'] ?? null,
        ];

        $errors = [];
        if (empty($data['_team1_id'])) {
            $errors[] = 'Team 1 is required.';
        }
        if (empty($data['_team2_id'])) {
            $errors[] = 'Team 2 is required.';
        }
        if (empty($data['_venue_id'])) {
            $errors[] = 'Venue is required.';
        }
        if (empty($data['event_date'])) {
            $errors[] = 'Event date is required.';
        }

        // Validation: Same teams
        if (!empty($data['_team1_id']) && !empty($data['_team2_id']) && $data['_team1_id'] === $data['_team2_id']) {
            $errors[] = 'Team 1 and Team 2 must be different.';
        }

        if (!empty($data['description']) && strlen($data['description']) > 180) {
            $errors[] = 'Description must be less than 180 characters.';
        }

        // If validation fails, return 400 with error messages
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            echo json_encode([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $errors,
            ]);
            return;
        }

        // Attempt to create the event
        try {
            Event::create($data);
            http_response_code(201); // Created
            echo json_encode([
                'status' => 'success',
                'message' => 'Event created successfully.',
            ]);
        } catch (\Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(), // Log or include only if safe
            ]);
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
