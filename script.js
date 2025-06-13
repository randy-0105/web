// Mensajes emergentes variados para la página principal
const toast = document.getElementById('toast');
const messages = [
    "Bienvenido a nuestra plataforma de apoyo.",
    "Recuerda tomar descansos regulares para cuidar la salud.",
    "Explora nuestros consejos para padres para mejorar tu experiencia.",
    "Disfruta de los juegos educativos para tus hijos.",
    "No olvides suscribirte para acceder a contenido exclusivo.",
    "Cada día es una nueva oportunidad para aprender y crecer.",
    "Tu dedicación hace la diferencia en la vida de tu hijo.",
    "Estamos aquí para apoyarte en cada paso del camino."
];

function showToast(message) {
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 6000);
}

// Mostrar mensajes emergentes secuenciales al cargar la página
let messageIndex = 0;
function showNextMessage() {
    showToast(messages[messageIndex]);
    messageIndex = (messageIndex + 1) % messages.length;
    setTimeout(showNextMessage, 12000);
}
window.onload = () => {
    showNextMessage();
};
