<?php

namespace SimpleEvents\Models;

use InvalidArgumentException;
use PDO;

class Event
{
    public static function create(array $data): void
    {
        self::validateRequiredFields($data, ['_team1_id', '_team2_id', '_venue_id', 'event_date']);

        // TODO: Get the team1 and team 2 sport, if it is the same return its id. This will ensure sport id is valid.
        // TODO: Check if it is not the same team, both teams exist.

        // Insert the event into the database
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO events (_team1_id, _team2_id, _venue_id, _sport_id, event_date, description)
            VALUES (:team1_id, :team2_id, :venue_id, :sport_id, :event_date, :description)
        ");
        $stmt->execute([
            ':team1_id' => $data['_team1_id'],
            ':team2_id' => $data['_team2_id'],
            ':venue_id' => $data['_venue_id'],
            ':sport_id' => $data['_sport_id'],
            ':event_date' => $data['event_date'],
            ':description' => $data['description'] ?? null,
        ]);
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
            e.description,
            e._sport_id 
        FROM events AS e
        JOIN teams AS t1 ON e._team1_id = t1.id
        JOIN teams AS t2 ON e._team2_id = t2.id
        JOIN venues AS v ON e._venue_id = v.id
        WHERE 1=1
    ";

        $params = [];

        // Filter by sport
        if (!empty($args['_sport_id'])) {
            $query .= " AND e._sport_id = :sport_id";
            $params[':sport_id'] = $args['_sport_id'];
        }

        // Filter by date
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

    /**
     * We will need to get the sports to display them in the form
     * @return array
     */
    public static function getSports(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM sports");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * We will need to get the teams to display them in the form
     * @return array
     */
    public static function getTeams(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM teams");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * We will need to get the venues to display them in the form
     * @return array
     */
    public static function getVenues(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM venues");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function isSameTeam(int $team1, int $team2)
    {
    }

    private static function getSport(int $team1, int $team2)
    {
        // TODO: Implement isSameSport() method. We need to make sure both teams are the same sport before creating an event. If not throw an exception.
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
