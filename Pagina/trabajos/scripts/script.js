function copiarCodigo() {
    const codigoInput = document.getElementById('codigo');
    codigoInput.select();
    document.execCommand('copy'); // Copiar el contenido del campo de texto

    const mensaje = document.getElementById('mensaje');
    mensaje.style.display = 'block'; // Mostrar mensaje de éxito
    setTimeout(() => {
        mensaje.style.display = 'none'; // Ocultar mensaje después de 2 segundos
    }, 2000);
}

function mostrarQR() {
    const qrCodeImg = document.getElementById('qrCode');
    const codigo = document.getElementById('codigo').value;
    
    // Aquí puedes generar el código QR usando una librería de generación de QR o una API
    qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?data=${codigo}&size=200x200`;
    
    document.getElementById('modal').style.display = 'block'; // Mostrar el modal
}

function cerrarModal() {
    document.getElementById('modal').style.display = 'none'; // Ocultar el modal
}
