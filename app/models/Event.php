<?php

namespace SimpleEvents\Models;

use InvalidArgumentException;
use PDO;

class Event
{
    /**
     * Get all events
     * @param array $args
     * @return array
     */
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
     * Create a new event
     * @param array $data
     * @return void
     * @throws InvalidArgumentException
     */
    public static function create(array $data): void
    {
        self::validateRequiredFields($data, ['_team1_id', '_team2_id', '_venue_id', 'event_date']);

        // Ensure both teams exist
        $team1 = self::getTeam($data['_team1_id']);
        $team2 = self::getTeam($data['_team2_id']);

        // Validate that teams are different
        self::isDifferentTeams($team1['id'], $team2['id']);

        // Ensure teams belong to the same sport
        $sportId = self::getMatchSportId($team1['id'], $team2['id']);

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
            ':sport_id' => $sportId,
            ':event_date' => $data['event_date'],
            ':description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Get all sports
     * @return array
     */
    public static function getSports(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM sports");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all teams
     * @return array
     */
    public static function getTeams(int $sportId): array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM teams WHERE _sport_id = :sport_id");
        $stmt->execute([':sport_id' => $sportId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a specific team by ID
     * @param int $teamId
     * @return array
     * @throws InvalidArgumentException
     */
    public static function getTeam(int $teamId): array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM teams WHERE id = :id");
        $stmt->execute([':id' => $teamId]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$team) {
            throw new InvalidArgumentException("Team with ID $teamId does not exist.");
        }

        return $team;
    }

    /**
     * Get all venues
     * @return array
     */
    public static function getVenues(): array
    {
        // TODO: Check if it is necessary to add the _sport_id to the venues table
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM venues");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ensure two teams are different
     * @param int $team1
     * @param int $team2
     * @return void
     * @throws InvalidArgumentException
     */
    private static function isDifferentTeams(int $team1, int $team2): void
    {
        if ($team1 === $team2) {
            throw new InvalidArgumentException("The teams must be different.");
        }
    }

    /**
     * Get the sport ID if both teams belong to the same sport
     * @param int $team1
     * @param int $team2
     * @return int
     * @throws InvalidArgumentException
     */
    private static function getMatchSportId(int $team1, int $team2): int
    {
        $team1Data = self::getTeam($team1);
        $team2Data = self::getTeam($team2);

        if ($team1Data['_sport_id'] !== $team2Data['_sport_id']) {
            throw new InvalidArgumentException("The teams must belong to the same sport.");
        }

        return (int)$team1Data['_sport_id'];
    }

    /**
     * Validate required fields
     * @param array $data
     * @param array $requiredFields
     * @return void
     * @throws InvalidArgumentException
     */
    private static function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("The field '$field' is required.");
            }
        }
    }
}
