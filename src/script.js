const body = document.querySelector('body');
const form = document.querySelector('.loginForm');
const input = document.querySelector('.input');

form.addEventListener('submit', function (e) {
	e.preventDefault();
	requestToDb();
})

input.addEventListener('input', debounce(requestToDb, 400))

async function requestToDb() {
	let response = await fetch('src/DB.php', {
		method: "POST",
		body: new FormData(form),
	})
	if (!response.ok) {
		alert(response.status + " Not Found");
	}
	let result = await response.json();
	if (result === 'Такого E-mail  не существует') {
		let value = input.value;
		body.insertAdjacentHTML('beforeend', `<br><div style="color: red">Записей для email ${value} не обнаруженно!</div>`)
	}
	if (Array.isArray(result)) {
		result.map(user => {
			printUser(user)
		})
	}
}

function debounce(f, ms) {
	let timeOut;
	return function () {
		const fnCall = () => {
			f.apply(this, arguments)
		}
		clearTimeout(timeOut);
		timeOut = setTimeout(fnCall, ms)
	}
}

function printUser(user) {
	body.insertAdjacentHTML('beforeend', `<br><div style="color: green">${user.email} - ${user.name} ${user.sname} [id = ${user.user_id}]</div>`);
}
