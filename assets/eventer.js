export class Eventer {

	#receivers;

	constructor(receivers = []) {
		this.#receivers = receivers;
		this.#receivers.push(new InternalReceiver());
	}

	initialize() {
		const stack = window.eventerDataLayer || [];

		window.eventerDataLayer = {
			push: this.#push.bind(this),
		};
		window.eventer = this.push.bind(this);

		stack.forEach(args => {
			this.#push(...args);
		});
	}

	/**
	 * @param {String} receiver
	 * @param {Array} args
	 */
	push(receiver, args) {
		this.#processMessage(receiver, args);
	}

	#push(...args) {
		if (args.length !== 2) {
			console.warn(`Invalid number of arguments. Expected eventer({recipient}, {arrayOfArguments}).`);

			return;
		}

		this.#processMessage(args[0], args[1]);
	}

	#processMessage(recipient, content) {
		if (typeof recipient !== 'string') {
			console.warn(`Recipient is not a string.`);

			return;
		}

		if (!Array.isArray(content)) {
			console.warn(`Content is not an array.`);

			return;
		}

		for (const receiver of this.#receivers) {
			if (receiver.receive(recipient, content) === true) {
				return;
			}
		}

		console.warn(`No receiver found for recipient "${recipient}".`);
	}

}

export class Receiver {

	/**
	 * @param {String} recipient
	 * @param {Array} content
	 * @return {Boolean}
	 */
	receive(recipient, content)
	{
		throw new Error('Not implemented.');
	}

}

class InternalReceiver extends Receiver {


	receive(recipient, content) {
		if (recipient !== 'internal') {
			return false;
		}

		if (content[0] === 'removeQuery' && typeof content[1] === 'string') {
			const url = new URL(window.location.toString());

			if (!url.searchParams.has(content[1])) {
				console.warn(`Query parameter "${content[1]}" does not exist in URL.`);

				return;
			}

			url.searchParams.delete(content[1]);
			window.history.replaceState({}, null, url.toString());
		}

		return true;
	}

}

export class FacebookReceiver extends Receiver {

	#debugMode;

	constructor(debugMode = false) {
		super();

		this.#debugMode = debugMode;
	}

	receive(recipient, content) {
		if (recipient !== 'fbq') {
			return false;
		}

		if (this.#debugMode) {
			console.log(`Eventer received: ${recipient}`, content);

			return true;
		}

		if (typeof window.fbq === 'undefined') {
			console.warn(`fbq does not exist in global scope.`);

			return true;
		}

		window.fbq(...content);

		return true;
	}

}

export class GoogleReceiver extends Receiver {

	#debugMode;

	constructor(debugMode = false) {
		super();

		this.#debugMode = debugMode;
	}

	receive(recipient, content) {
		if (recipient !== 'gtag') {
			return false;
		}

		if (this.#debugMode) {
			console.log(`Eventer received: ${recipient}`, content);

			return true;
		}

		if (typeof window.gtag === 'undefined') {
			console.warn(`gtag does not exist in global scope.`);

			return true;
		}

		window.gtag(...content);

		return true;
	}

}

export function createDefaultEventer(debugMode = false) {
	const eventer = new Eventer([
		new GoogleReceiver(debugMode),
		new FacebookReceiver(debugMode),
	]);

	eventer.initialize();

	return eventer;
}
