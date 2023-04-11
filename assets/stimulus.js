import { Controller } from "@hotwired/stimulus";

/**
 * @property {Array} eventsValue
 * @property {HTMLElement} element
 */
export default class EventerController extends Controller {

	static values = {
		events: Array,
	};

	#clickMessages = [];
	#displayMessages = [];
	/** @type {IntersectionObserver|null} */
	#observer = null;

	connect() {
		const clickMessages = [];
		const displayMessages = [];

		for (const event of this.eventsValue) {
			const eventName = event[0];

			if (typeof eventName !== 'string') {
				console.warn('Event name must be a string, given: ', eventName);

				continue;
			}

			if (!Array.isArray(event[1])) {
				console.warn('Event content must be an array, given: ', event[1]);

				continue;
			}

			const push = (array) => {
				for (const message of event[1]) {
					array.push(message);
				}
			};

			if (eventName === 'click') {
				push(clickMessages);

			} else if (eventName === 'display') {
				push(displayMessages);

			} else {
				console.warn('Unknown event name: ', eventName);

			}
		}

		this.#clickMessages = clickMessages;
		this.#displayMessages = displayMessages;

		if (displayMessages.length) {
			this.#observer = new IntersectionObserver(this.#handleDisplay.bind(this));
			this.#observer.observe(this.element);
		}

		if (clickMessages.length) {
			this.element.addEventListener('click', this.#handleClick.bind(this));
		}
	}

	#handleDisplay(entries) {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				this.#processMessages(this.#displayMessages);

				this.#displayMessages = [];

				this.#observer.disconnect();
				this.#observer = null;
			}
		});
	}

	#handleClick() {
		this.#processMessages(this.#clickMessages);

		this.#clickMessages = [];
		this.element.removeEventListener('click', this.#handleClick.bind(this));
	}

	#processMessages(messages) {
		for (const message of messages) {
			const receiver = message[0];
			const content = message[1];

			if (typeof receiver !== 'string') {
				console.warn('Receiver must be a string, given: ', receiver);

				continue;
			}

			if (!Array.isArray(content)) {
				console.warn('Content must be an array, given: ', content);

				continue;
			}

			window.eventer(receiver, content);
		}
	}

	disconnect() {
		if (this.#clickMessages.length) {
			this.element.removeEventListener('click', this.#handleClick.bind(this));
		}

		this.#observer?.disconnect();
	}

}
