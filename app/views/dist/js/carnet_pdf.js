document.getElementById('descargar').addEventListener('click', async () => {
    const btn = document.getElementById('descargar');
    btn.disabled = true;
    btn.textContent = 'Generando PDF...';

    try {
        const carnet = document.getElementById('carnet');

        const CARNET_W = 550;
        const CARNET_H = 400;

        // Pausa para asegurar renderizado completo
        await new Promise(resolve => setTimeout(resolve, 400));

        const canvas = await html2canvas(carnet, {
            scale: 3,
            allowTaint: true,       // Permitir sin restricciones CORS
            backgroundColor: '#ffffff',
            logging: false,
            imageTimeout: 15000,
            width:        CARNET_W,
            height:       CARNET_H,
            windowWidth:  CARNET_W,
            windowHeight: CARNET_H,
            x: 0,
            y: 0,
            scrollX: 0,
            scrollY: 0,
            onclone: function(clonedDoc) {
                // Ocultar botón en el clon
                const btnClon = clonedDoc.getElementById('descargar');
                if (btnClon) btnClon.style.display = 'none';

                // Forzar dimensiones exactas del carnet
                const clonedCarnet = clonedDoc.getElementById('carnet');
                if (clonedCarnet) {
                    clonedCarnet.style.width      = CARNET_W + 'px';
                    clonedCarnet.style.height     = CARNET_H + 'px';
                    clonedCarnet.style.minHeight  = CARNET_H + 'px';
                    clonedCarnet.style.overflow   = 'hidden';
                    clonedCarnet.style.display    = 'block';
                    clonedCarnet.style.visibility = 'visible';
                    clonedCarnet.style.opacity    = '1';
                }

                // Forzar contenedor izquierdo
                const decorIzq = clonedDoc.querySelector('.decorativo-izquierda');
                if (decorIzq) {
                    decorIzq.style.position   = 'absolute';
                    decorIzq.style.left       = '0';
                    decorIzq.style.top        = '0';
                    decorIzq.style.width      = '150px';
                    decorIzq.style.height     = CARNET_H + 'px';
                    decorIzq.style.overflow   = 'visible';
                    decorIzq.style.display    = 'block';
                    decorIzq.style.visibility = 'visible';
                    decorIzq.style.opacity    = '1';
                }

                // Forzar imagen vertical_fondo.png
                const capaCamiseta = clonedDoc.querySelector('.capa-camiseta');
                if (capaCamiseta) {
                    capaCamiseta.style.position   = 'absolute';
                    capaCamiseta.style.left       = '5px';
                    capaCamiseta.style.top        = '0';
                    capaCamiseta.style.width      = '150px';
                    capaCamiseta.style.height     = CARNET_H + 'px';
                    capaCamiseta.style.objectFit  = 'cover';
                    capaCamiseta.style.opacity    = '0.4';
                    capaCamiseta.style.zIndex     = '1';
                    capaCamiseta.style.display    = 'block';
                    capaCamiseta.style.visibility = 'visible';
                }

                // Forzar overlay de color del mes
                const colorOverlay = clonedDoc.querySelector('.capa-color-overlay');
                if (colorOverlay) {
                    colorOverlay.style.position = 'absolute';
                    colorOverlay.style.left     = '0';
                    colorOverlay.style.top      = '0';
                    colorOverlay.style.width    = '150px';
                    colorOverlay.style.height   = CARNET_H + 'px';
                    colorOverlay.style.zIndex   = '2';
                    colorOverlay.style.display  = 'block';
                }

                // Forzar decorativo derecha
                const decorDer = clonedDoc.querySelector('.decorativo-derecha');
                if (decorDer) {
                    decorDer.style.height     = CARNET_H + 'px';
                    decorDer.style.display    = 'block';
                    decorDer.style.visibility = 'visible';
                    decorDer.style.opacity    = '0.85';
                }
            }
        });

        const imgData = canvas.toDataURL('image/png');

        const px_to_mm   = 25.4 / 96;
        const carnetW_mm = CARNET_W * px_to_mm;
        const carnetH_mm = CARNET_H * px_to_mm;

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: [carnetH_mm, carnetW_mm]
        });

        pdf.addImage(imgData, 'PNG', 0, 0, carnetW_mm, carnetH_mm, '', 'FAST');

        const nombreArchivo = 'carnet_'
            + datosPersona.nombres.replace(/\s+/g, '_')
            + '_'
            + datosPersona.apellidos.replace(/\s+/g, '_')
            + '.pdf';

        pdf.save(nombreArchivo);

    } catch (error) {
        console.error('Error al generar PDF:', error);
        alert('Ocurrió un error al generar el PDF. Por favor intente nuevamente.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Descargar PDF';
    }
});