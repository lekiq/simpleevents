<?php
/**
 * Index page for displaying events.
 *
 * This file renders the filters section and the events listing
 * based on the user's input or predefined conditions. Events
 * can be filtered by sport and date using the provided dropdown
 * and date picker.
 *
 * @var array $events List of events to display. Each event is an associative array with the following keys:
 *                    - sport (string): The sport type (e.g., Football, Basketball).
 *                    - teams (string): The competing teams (e.g., Team 1 vs Team 2).
 *                    - date (string): The date of the event (e.g., YYYY-MM-DD).
 *                    - venue (string): The event location.
 *                    - description (string): A brief description of the event.
 *
 * @todo Fetch options for the sports dropdown dynamically using vanilla JS or a library like jQuery or Alpine.js.
 * @todo Finish the logics of filters, check GET parameters and if set, set as already selected
 * @todo: Add a hidden div which will be used as a modal to for the new event form
 */
?>


<section class="filters">
    <h2 class="filters__title">Filters</h2>
    <div class="filters__options">
        <select id="sportsDropdown" class="filters__dropdown">
            <option value="">All Sports</option>
        </select>
        <input type="date" id="datePicker" class="filters__date-picker"/>
    </div>
</section>
<section class="events">
    <h2 class="events__title"><?php echo count($events) ?> events match current filters</h2>
    <?php if ($events) : ?>
        <div class="events__list">
            <?php foreach ($events as $event) : ?>
                <article class="event-card">
                    <p class="event-card__sport"><?php echo $event['sport'] ?></p>
                    <p class="event-card__teams"><?php echo $event['team1'] ?> vs <?php echo $event['team2'] ?></p>
                    <p class="event-card__date"><?php echo date('H:i - d.m.Y', strtotime($event['event_date'])) ?></p>
                    <p class="event-card__venue"><?php echo $event['venue'] ?></p>
                    <p class="event-card__description"><?php echo $event['description'] ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p class="events__no-results">No events found.</p>
    <?php endif; ?>
</section>