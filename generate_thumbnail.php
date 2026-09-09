<?php
require_once 'services/connection.php';

class VideoThumbnailService
{
    private const THUMB_DIR = __DIR__ . '/assets/img/projects/thumbs';
    private const FRAME_TIME = 0.54;

    public static function handle()
    {
        $projectId = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;
        $frameTime = isset($_GET['t']) && is_numeric($_GET['t']) ? max(0, (float) $_GET['t']) : self::FRAME_TIME;

        if ($projectId <= 0) {
            self::error();
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT video_url FROM projects WHERE id = ?');
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = $result->fetch_assoc();
        $stmt->close();

        if (!$project || empty($project['video_url'])) {
            self::error();
        }

        $videoUrl = trim($project['video_url']);
        $hash = md5($videoUrl . '|' . $frameTime);
        $thumbFile = self::THUMB_DIR . '/' . $hash . '.jpg';

        if (!is_file($thumbFile)) {
            self::generate($videoUrl, $frameTime, $thumbFile, $hash);
        }

        if (!is_file($thumbFile)) {
            self::error();
        }

        self::serve($thumbFile, $hash);
    }

    private static function generate($videoUrl, $frameTime, $thumbFile, $hash)
    {
        if (!is_dir(self::THUMB_DIR) && !mkdir(self::THUMB_DIR, 0775, true)) {
            return;
        }

        $lockFile = self::THUMB_DIR . '/' . $hash . '.lock';
        $lock = fopen($lockFile, 'c');
        if (!$lock || !flock($lock, LOCK_EX)) {
            return;
        }

        if (is_file($thumbFile)) {
            flock($lock, LOCK_UN);
            fclose($lock);
            return;
        }

        $tmpVideo = tempnam(sys_get_temp_dir(), 'gdrive_');
        $tmpFrame = tempnam(sys_get_temp_dir(), 'gfrm_') . '.jpg';

        try {
            if (!self::downloadFromDrive($videoUrl, $tmpVideo)) {
                return;
            }

            $cmd = 'ffmpeg -y -ss ' . (float) $frameTime
                . ' -i ' . escapeshellarg($tmpVideo)
                . ' -frames:v 1 -q:v 2 ' . escapeshellarg($tmpFrame)
                . ' 2>&1';
            exec($cmd, $output, $code);

            if ($code === 0 && is_file($tmpFrame) && filesize($tmpFrame) > 1000) {
                rename($tmpFrame, $thumbFile);
            }
        } finally {
            @unlink($tmpVideo);
            @unlink($tmpFrame);
            @unlink($lockFile);
            if (isset($lock)) {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    private static function downloadFromDrive($url, $dest)
    {
        if (!preg_match('#/file/d/([a-zA-Z0-9_-]+)#', $url, $m)) {
            return false;
        }
        $fileId = $m[1];

        $content = self::download('https://drive.google.com/uc?export=download&id=' . $fileId);
        if ($content === false) {
            return false;
        }

        if (stripos($content[0], 'text/html') !== false || strpos($content[1], '<html') !== false && stripos($content[1], 'text/html') !== false) {
            $html = $content[1];
            if (!preg_match('/name="confirm"\s+value="([a-zA-Z0-9_-]+)"/', $html, $cp)) {
                return false;
            }
            if (!preg_match('/name="uuid"\s+value="([^"]+)"/', $html, $up)) {
                return false;
            }
            if (!preg_match('/action="([^"]+)"/', $html, $ap)) {
                $ap[1] = 'https://drive.usercontent.google.com/download';
            }

            $confirmUrl = str_replace('&amp;', '&', $ap[1])
                . (strpos($ap[1], '?') === false ? '?' : '&')
                . 'id=' . $fileId . '&export=download&confirm=' . $cp[1] . '&uuid=' . $up[1];
            return self::downloadToFile($confirmUrl, $dest);
        }

        return file_put_contents($dest, $content[1]) !== false;
    }

    private static function download($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 120,
        ]);
        $body = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] >= 400 || $body === false || $body === '') {
            return false;
        }

        return [$info['content_type'], $body];
    }

    private static function downloadToFile($url, $dest)
    {
        $fp = fopen($dest, 'wb');
        if (!$fp) {
            return false;
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 120,
        ]);
        curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        return $code < 400 && filesize($dest) > 1000;
    }

    private static function serve($file, $etag)
    {
        header('Content-Type: image/jpeg');
        header('Cache-Control: no-cache');
        header('ETag: "' . $etag . '"');

        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH'], '"') === $etag) {
            http_response_code(304);
            return;
        }

        http_response_code(200);
        readfile($file);
    }

    private static function error()
    {
        http_response_code(404);
        exit;
    }
}

VideoThumbnailService::handle();
?>