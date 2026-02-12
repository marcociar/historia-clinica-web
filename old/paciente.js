async function guardarHistoria() {
const observaciones = document.getElementById('observaciones').value;


await fetch('/historia', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({ observaciones })
});


alert('Historia actualizada');
}