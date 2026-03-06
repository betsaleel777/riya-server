<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileDownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            throw new NotFoundHttpException('Le paramètre path est requis.');
        }

        // Extraire le chemin depuis l'URL (ex: http://localhost:8000/media/186/296143.png -> /media/186/296143.png)
        $parsedUrl = parse_url($path);
        $relativePath = $parsedUrl['path'] ?? $path;

        // Extraire le chemin relatif pour Storage (ex: /media/186/296143.png -> 186/296143.png)
        // On cherche "/media/" dans le chemin et on prend tout ce qui suit
        $mediaPos = strpos($relativePath, '/media/');
        if ($mediaPos !== false) {
            $storagePath = substr($relativePath, $mediaPos + 7); // +7 pour sauter "/media/"
        } elseif (str_starts_with($relativePath, '/media')) {
            $storagePath = ltrim($relativePath, '/media/');
        } else {
            throw new NotFoundHttpException('Le chemin doit contenir /media');
        }

        // Vérifier que le fichier existe sur le disque media
        if (!Storage::disk('media')->exists($storagePath)) {
            throw new NotFoundHttpException('Le fichier demandé n\'existe pas.');
        }
        return Storage::disk('media')->response($storagePath);
    }
}
