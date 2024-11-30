class EventManager {
	constructor() {
		this.elements = {
			sportDropdown: document.getElementById('sport_id'),
			team1Dropdown: document.getElementById('team1_id'),
			team2Dropdown: document.getElementById('team2_id'),
			sportFilterDropdown: document.getElementById('sport_id_filter'),
			datePicker: document.getElementById('datePicker'),
			addEventForm: document.getElementById('addEventForm'),
			addEventModal: document.getElementById('addEventModal')
		};

		this.init();
	}

	init() {
		this.initializeDropdowns();
		this.setFiltersFromParams();
		this.bindEvents();
	}

	initializeDropdowns() {
		this.populateDropdown('/api/sports', 'sport_id_filter');
		this.populateDropdown('/api/sports', 'sport_id');
		this.populateDropdown('/api/venues', 'venue_id');
	}

	bindEvents() {
		this.elements.sportDropdown.addEventListener('change', this.handleSportChange.bind(this));
		this.elements.addEventForm.addEventListener('submit', this.handleFormSubmit.bind(this));
	}

	handleSportChange(event) {
		const sportId = event.target.value;

		if (sportId) {
			this.populateDropdown(`/api/teams?_sport_id=${sportId}`, 'team1_id');
			this.populateDropdown(`/api/teams?_sport_id=${sportId}`, 'team2_id');
		} else {
			this.clearDropdown(this.elements.team1Dropdown, 'Select Team 1');
			this.clearDropdown(this.elements.team2Dropdown, 'Select Team 2');
		}
	}

	handleFormSubmit(event) {
		event.preventDefault();
		const formData = new FormData(event.target);

		fetch('/events', {
			method: 'POST',
			body: formData,
		})
			.then(this.handleSubmissionResponse.bind(this))
			.catch(this.handleSubmissionError);
	}

	async handleSubmissionResponse(response) {
		const data = await response.json();

		if (response.ok) {
			alert(data.message);
			this.closeModal();
			location.reload();
		} else {
			const errorMessage = data.errors
				? `Validation errors:\n${data.errors.join('\n')}`
				: `Error: ${data.message}`;
			alert(errorMessage);
		}
	}

	handleSubmissionError(error) {
		alert('An unexpected error occurred.');
		console.error('Error:', error);
	}

	clearDropdown(dropdown, defaultText) {
		dropdown.innerHTML = '';
		const defaultOption = document.createElement('option');
		defaultOption.value = '';
		defaultOption.textContent = defaultText;
		dropdown.appendChild(defaultOption);
	}

	populateDropdown(apiUrl, dropdownId) {
		const params = new URLSearchParams(window.location.search);
		const preselectedValue = params.get(dropdownId === 'sport_id_filter' ? '_sport_id' : dropdownId);

		fetch(apiUrl)
			.then(response => response.json())
			.then(data => {
				const dropdown = document.getElementById(dropdownId);
				dropdown.innerHTML = '';

				const defaultOption = document.createElement('option');
				defaultOption.value = '';
				defaultOption.textContent = 'Select Option';
				dropdown.appendChild(defaultOption);

				data.forEach(item => {
					const option = document.createElement('option');
					option.value = item.id;
					option.textContent = item.name;
					dropdown.appendChild(option);
				});

				if (preselectedValue) {
					dropdown.value = preselectedValue;
				}
			})
			.catch(error => console.error(`Error fetching data for ${dropdownId}:`, error));
	}

	openModal() {
		this.elements.addEventModal.classList.remove('hidden');
		this.elements.addEventModal.style.opacity = '1';
		this.elements.addEventModal.style.pointerEvents = 'auto';
	}

	closeModal() {
		this.elements.addEventModal.classList.add('hidden');
		this.elements.addEventModal.style.opacity = '0';
		this.elements.addEventModal.style.pointerEvents = 'none';
	}

	applyFilters() {
		const sportId = this.elements.sportFilterDropdown.value;
		const selectedDate = this.elements.datePicker.value;

		const params = new URLSearchParams(window.location.search);

		if (sportId) params.set('_sport_id', sportId);
		else params.delete('_sport_id');

		if (selectedDate) params.set('date', selectedDate);
		else params.delete('date');

		window.location.search = params.toString();
	}

	setFiltersFromParams() {
		const params = new URLSearchParams(window.location.search);
		const sportId = params.get('_sport_id');
		const date = params.get('date');

		if (sportId) {
			const sportDropdown = document.getElementById('sport_id_filter');
			if (sportDropdown) {
				sportDropdown.value = sportId;
			}
		}

		if (date) {
			const datePicker = document.getElementById('datePicker');
			if (datePicker) {
				datePicker.value = date;
			}
		}
	}
}

// Initialize the EventManager when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
	const eventManager = new EventManager();

	// Expose methods globally for HTML event handlers
	window.applyFilters = () => eventManager.applyFilters();
	window.openModal = () => eventManager.openModal();
	window.closeModal = () => eventManager.closeModal();
});
