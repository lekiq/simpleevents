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
 *                    - description (string): A brief description of the event. Max characters 180.
 *
 */
?>


<section class="filters">
    <h2 class="filters__title">Filters</h2>
    <div class="filters__options">
        <select id="sport_id_filter" name="_sport_id" class="filters__dropdown">
        </select>
        <input type="date" id="datePicker" class="filters__date-picker"/>
		<button class="button" onclick="applyFilters()">Apply</button>
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
        <p>No events found.</p>
    <?php endif; ?>
</section>

<div id="addEventModal" class="modal hidden">
	<div class="modal__content">
		<form id="addEventForm" action="/events" method="POST" class="modal__form mb-1">
			<label for="sport_id">Sport</label>
			<select id="sport_id" name="_sport_id" required>
				<!-- Options dynamically populated -->
			</select>

			<label for="team1_id">Team 1:</label>
			<select id="team1_id" name="_team1_id" required>
				<!-- Options dynamically populated -->
			</select>

			<label for="team2_id">Team 2:</label>
			<select id="team2_id" name="_team2_id" required>
				<!-- Options dynamically populated -->
			</select>

			<label for="venue_id">Venue:</label>
			<select id="venue_id" name="_venue_id" required>
				<!-- Options dynamically populated -->
			</select>

			<label for="event_date">Event Date:</label>
			<input type="datetime-local" id="event_date" name="event_date" required>

			<label for="description">Description:</label>
			<textarea id="description" name="description" rows="4"></textarea>

			<button type="submit" class="button">Add Event</button>
		</form>
		<button class="button" onclick="closeModal()">Close</button>
	</div>
</div>