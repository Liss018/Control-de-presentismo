// Crear el elemento de video
const video = document.getElementById("video");
const canvasElement = document.getElementById('qr-canvas');
const context = canvasElement.getContext('2d', { willReadFrequently: true });

// Div donde llegará nuestro canvas
const btnScanQR = document.getElementById("btn-scan-qr");

// Lectura desactivada
let scanning = false;

// Función para encender la cámara
const encenderCamara = () => {
    navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment", width: { ideal: 640 }, height: { ideal: 480 } } })
        .then(function (stream) {
            scanning = true;
            btnScanQR.hidden = true;
            canvasElement.hidden = false;
            video.srcObject = stream;
            video.play();
            tick();
            scan();
        })
        .catch((error) => {
            console.error("Error al acceder a la cámara: ", error);
        });
};

// Funciones para levantar las funciones de encendido de la cámara
function tick() {
    if (scanning) {
        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        context.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        requestAnimationFrame(tick);
    }
}

function scan() {
    try {
        qrcode.decode();
    } catch (e) {
        setTimeout(scan, 100); // Aumenta la frecuencia de escaneo a 100 ms
    }
}

// Apagar la cámara
const cerrarCamara = () => {
    video.srcObject.getTracks().forEach((track) => {
        track.stop();
    });
    canvasElement.hidden = true;
    btnScanQR.hidden = false;
};

// Activar sonido
const activarSonido = () => {
    const audio = document.getElementById('audioScaner');
    audio.play();
}

// Callback cuando termina de leer el código QR
qrcode.callback = (respuesta) => {
    if (respuesta) {
        document.getElementById('qr-text').textContent = respuesta; // Muestra el texto escaneado
        document.getElementById('scan-result').style.display = 'flex'; // Muestra la sección de resultado
        activarSonido();
        cerrarCamara();
    }
};

// Función para copiar el texto al portapapeles
function copiarTexto() {
    const texto = document.getElementById('qr-text').textContent;
    navigator.clipboard.writeText(texto).then(() => {
        alert('Texto copiado: ' + texto);
    }).catch(err => {
        console.error('Error al copiar: ', err);
    });
}

// Evento para mostrar la cámara sin el botón 
window.addEventListener('load', (e) => {
    encenderCamara();
});
