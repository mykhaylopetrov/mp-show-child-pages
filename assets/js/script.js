/**
 * Tabs
 */
const checkElement = (id) => {
	document
		.querySelectorAll(".mptabs .tabs__list-item")
		.forEach((el) => (el.hidden = el.dataset["name"] !== id));
	};

	document
	.querySelector(".mptabs .tabs__select")
	.addEventListener("change", (ev) => checkElement(ev.target.value));

	checkElement("id-all"); // set default element id
