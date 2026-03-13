<?php
/**
 * Genera la tarjeta de cumpleaños como imagen JPEG.
 * Ruta: cumpleaniosTarjeta/{alumno_id}/
 *
 * La foto del alumno se coloca en el rectángulo blanco de la plantilla marcocp.jpeg.
 * Las proporciones del rectángulo se definen abajo y pueden ajustarse si cambia la plantilla.
 */
	use app\controllers\cumpleaniosController;

	$insCumple = new cumpleaniosController();

	// Validar ID
	$alumno_id = isset($url[1]) ? (int)$insCumple->limpiarCadena($url[1]) : 0;
	if ($alumno_id <= 0) {
		http_response_code(400);
		exit('ID de alumno no válido.');
	}

	// Obtener datos del alumno
	$datos = $insCumple->infoAlumno($alumno_id);
	if ($datos->rowCount() !== 1) {
		http_response_code(404);
		exit('Alumno no encontrado.');
	}
	$alumno = $datos->fetch();

	// ── Rutas físicas ──────────────────────────────────────────
	$base_path    = realpath(__DIR__ . '/../../..');
	$marco_path   = $base_path . '/app/views/imagenes/cumples/marcocp.jpeg';
	$foto_dir     = $base_path . '/app/views/imagenes/fotos/alumno/';
	$default_foto = $foto_dir  . 'alumno.png';

	// ── Cargar plantilla ───────────────────────────────────────
	if (!file_exists($marco_path)) {
		http_response_code(500);
		exit('Plantilla no encontrada.');
	}
	$marco = imagecreatefromjpeg($marco_path);
	if (!$marco) {
		http_response_code(500);
		exit('No se pudo cargar la plantilla.');
	}
	$marco_w = imagesx($marco);
	$marco_h = imagesy($marco);

	// ── Proporciones del rectángulo blanco en marcocp.jpeg ─────
	// Ajustar estos valores si se cambia la plantilla.
	// Coordenadas del interior blanco (sin el borde amarillo).
	$rect_x = (int)($marco_w * 0.226);   // borde izquierdo del área blanca
	$rect_y = (int)($marco_h * 0.303);   // borde superior del área blanca
	$rect_w = (int)($marco_w * 0.558);   // ancho del área blanca
	$rect_h = (int)($marco_h * 0.462);   // alto del área blanca

	// ── Cargar foto del alumno ─────────────────────────────────
	$foto_path = ($alumno['alumno_imagen'] !== '')
		? $foto_dir . $alumno['alumno_imagen']
		: $default_foto;

	if (!file_exists($foto_path)) {
		$foto_path = $default_foto;
	}

	$ext      = strtolower(pathinfo($foto_path, PATHINFO_EXTENSION));
	$foto_src = null;
	if      ($ext === 'jpg' || $ext === 'jpeg') { $foto_src = @imagecreatefromjpeg($foto_path); }
	elseif  ($ext === 'png')                    { $foto_src = @imagecreatefrompng($foto_path);  }
	elseif  ($ext === 'gif')                    { $foto_src = @imagecreatefromgif($foto_path);  }

	if (!$foto_src && file_exists($default_foto)) {
		$foto_src = @imagecreatefrompng($default_foto);
	}

	// ── Encajar la foto en el rectángulo blanco ────────────────
	if ($foto_src) {
		$foto_orig_w = imagesx($foto_src);
		$foto_orig_h = imagesy($foto_src);

		// Centro-recortar la foto para que coincida con la relación de aspecto del rectángulo
		$aspecto_rect = $rect_w / $rect_h;
		$aspecto_foto = $foto_orig_w / $foto_orig_h;

		if ($aspecto_foto > $aspecto_rect) {
			// La foto es más ancha que el rectángulo: recortar laterales
			$src_h = $foto_orig_h;
			$src_w = (int)($foto_orig_h * $aspecto_rect);
			$src_x = (int)(($foto_orig_w - $src_w) / 2);
			$src_y = 0;
		} else {
			// La foto es más alta que el rectángulo: recortar arriba/abajo
			$src_w = $foto_orig_w;
			$src_h = (int)($foto_orig_w / $aspecto_rect);
			$src_x = 0;
			$src_y = (int)(($foto_orig_h - $src_h) / 2);
		}

		// Redimensionar directamente sobre la plantilla
		imagecopyresampled(
			$marco, $foto_src,
			$rect_x, $rect_y,       // destino: esquina superior-izquierda del rectángulo
			$src_x,  $src_y,        // origen: inicio del recorte
			$rect_w, $rect_h,       // tamaño destino
			$src_w,  $src_h         // tamaño origen (recortado)
		);

		imagedestroy($foto_src);
	}

	// ── Nombre del alumno sobre la franja inferior de la foto ──
	$nombre = strtoupper(
		trim($alumno['alumno_primernombre'] . ' ' . $alumno['alumno_segundonombre'])
		. ' ' .
		trim($alumno['alumno_apellidopaterno'] . ' ' . $alumno['alumno_apellidomaterno'])
	);
	$edad = (int)$alumno['edad'] + 1;

	// Buscar fuente TrueType (Windows + ruta del proyecto)
	$font_paths = [
		$base_path . '/app/views/dist/fonts/sourcesanspro/SourceSansPro-Bold.ttf',
		$base_path . '/app/views/dist/fonts/OpenSans-Bold.ttf',
		'C:/Windows/Fonts/arialbd.ttf',
		'C:/Windows/Fonts/arial.ttf',
	];
	$font_file = null;
	foreach ($font_paths as $fp) {
		if (file_exists($fp)) { $font_file = $fp; break; }
	}

	// Franja semitransparente en la parte inferior del rectángulo para el nombre
	$franja_h     = (int)($rect_h * 0.18);
	$franja_y     = $rect_y + $rect_h - $franja_h;
	$color_azul   = imagecolorallocate($marco, 0, 42, 100);
	$color_amarillo = imagecolorallocate($marco, 255, 224, 1);
	$color_blanco = imagecolorallocate($marco, 255, 255, 255);

	// Superponer un rectángulo semi-opaco (mezcla píxel a píxel para transparencia ~65%)
	$overlay = imagecreatetruecolor($rect_w, $franja_h);
	$bg_color = imagecolorallocate($overlay, 0, 42, 100);
	imagefilledrectangle($overlay, 0, 0, $rect_w, $franja_h, $bg_color);
	imagecopymerge($marco, $overlay, $rect_x, $franja_y, 0, 0, $rect_w, $franja_h, 70);
	imagedestroy($overlay);

	// Texto del nombre centrado en la franja
	if ($font_file) {
		$fs_nombre = max(8, (int)($rect_w * 0.055));
		$fs_edad   = max(7, (int)($rect_w * 0.040));

		// Nombre (una línea; si es muy largo, reducir fuente)
		$bbox_n = imagettfbbox($fs_nombre, 0, $font_file, $nombre);
		$tw_n   = abs($bbox_n[4] - $bbox_n[0]);
		// Reducir si excede el ancho del rectángulo
		while ($tw_n > $rect_w - 20 && $fs_nombre > 7) {
			$fs_nombre--;
			$bbox_n = imagettfbbox($fs_nombre, 0, $font_file, $nombre);
			$tw_n   = abs($bbox_n[4] - $bbox_n[0]);
		}
		$tx_n = $rect_x + (int)(($rect_w - $tw_n) / 2);
		$ty_n = $franja_y + (int)($franja_h * 0.48);
		imagettftext($marco, $fs_nombre, 0, $tx_n, $ty_n, $color_blanco, $font_file, $nombre);

		// Edad centrada debajo del nombre
		$texto_edad = $edad . ' AÑOS';
		$bbox_e = imagettfbbox($fs_edad, 0, $font_file, $texto_edad);
		$tw_e   = abs($bbox_e[4] - $bbox_e[0]);
		$tx_e   = $rect_x + (int)(($rect_w - $tw_e) / 2);
		$ty_e   = $franja_y + (int)($franja_h * 0.85);
		imagettftext($marco, $fs_edad, 0, $tx_e, $ty_e, $color_amarillo, $font_file, $texto_edad);

	} else {
		// Fallback: fuente de mapa de bits
		$gd_font = 4;
		$tw = strlen($nombre) * imagefontwidth($gd_font);
		imagestring($marco, $gd_font,
			$rect_x + (int)(($rect_w - $tw) / 2),
			$franja_y + 8,
			$nombre, $color_blanco);
		$txt_e = $edad . ' ANOS';
		$tw2   = strlen($txt_e) * imagefontwidth(3);
		imagestring($marco, 3,
			$rect_x + (int)(($rect_w - $tw2) / 2),
			$franja_y + 28,
			$txt_e, $color_amarillo);
	}

	// ── Enviar imagen al navegador ─────────────────────────────
	header('Content-Type: image/jpeg');
	header('Content-Disposition: inline; filename="cumpleanios_' . $alumno_id . '.jpg"');
	header('Cache-Control: max-age=3600');
	imagejpeg($marco, null, 92);
	imagedestroy($marco);
	exit;
