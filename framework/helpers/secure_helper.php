<?php
function secureArray($array_sec){
	foreach ($array_sec as $key => $value) {
		if(is_array($value)) {
			$array_sec[$key] = secureArray($value);
		}
		else {
			$array_sec[$key] = htmlspecialchars(trim($value), ENT_QUOTES);
		}
	}
	return $array_sec;
}

function checkExtension($filename, $ext){
	$mimeTypes = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
        );
	$ext = $mimeTypes[$ext];
	if (!empty($ext) && function_exists('mime_content_type')){
		$info = mime_content_type($filename);
		if ($ext === $info){
			return (true);
		}
	}
	return (false);
}