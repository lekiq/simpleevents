<?php

namespace SimpleEvents\Models;

use InvalidArgumentException;
use PDO;

class Event
{
    public static function create(array $data)
    {
        self::validateRequiredFields($data, ['_team1_id', '_team2_id', '_venue_id', 'event_date']);

        // Insert the event into the database
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO events (_team1_id, _team2_id, _venue_id, event_date, description)
            VALUES (:team1_id, :team2_id, :venue_id, :event_date, :description)
        ");
        $stmt->execute([
            ':team1_id' => $data['_team1_id'],
            ':team2_id' => $data['_team2_id'],
            ':venue_id' => $data['_venue_id'],
            ':event_date' => $data['event_date'],
            ':description' => $data['description'] ?? null,
        ]);

        // TODO: Implement create() method.
        //TODO: Check if it is not the same team, both teams exist, and both teams are the same sport.
    }

    public static function query(array $args = []): array
    {
        $db = Database::connect();

        // Base query
        $query = "
        SELECT 
            e.id, 
            t1.name AS team1, 
            t2.name AS team2, 
            v.name AS venue, 
            e.event_date, 
            e.description 
        FROM events AS e
        JOIN teams AS t1 ON e._team1_id = t1.id
        JOIN teams AS t2 ON e._team2_id = t2.id
        JOIN venues AS v ON e._venue_id = v.id
        WHERE 1=1
    ";

        $params = [];

        // Add filters dynamically based on input
        if (!empty($args['_team_id'])) {
            $query .= " AND (e._team1_id = :team_id OR e._team2_id = :team_id)";
            $params[':team_id'] = $args['_team_id'];
        }
        if (!empty($args['_venue_id'])) {
            $query .= " AND e._venue_id = :venue_id";
            $params[':venue_id'] = $args['_venue_id'];
        }
        if (!empty($args['date'])) {
            $query .= " AND DATE(e.event_date) = :event_date";
            $params[':event_date'] = $args['date'];
        }

        // Execute query
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    private static function isNotSameTeam(int $team1, int $team2)
    {
        // TODO: Implement isNotSameTeam() method. We need to make sure both teams are not the same before creating an event. If they are throw an exception.
    }

    private static function isSameSport(int $team1, int $team2)
    {
        // TODO: Implement isSameSport() method. We need to make sure both teams are the same sport before creating an event. If not throw an exception.
    }

    private static function teamExists(int $team)
    {
        // TODO: Implement teamExists() method. We need to make sure the team exists before creating an event. If not throw an exception.
    }

    private static function sportExists(int $sport)
    {
        // TODO: Implement sportExists() method. We need to make sure the sport exists before creating an event. If not throw an exception.
    }

    private static function venueExists(int $venue)
    {
        // TODO: Implement venueExists() method. We need to make sure the venue exists before creating an event. If not throw an exception.
    }

    private static function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("The field '$field' is required.");
            }
        }
    }
}
