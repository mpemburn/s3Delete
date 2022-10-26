<?php
namespace S3Delete;

use Aws\S3\S3Client;

class AssetDeleted
{
    protected S3Client $s3Client;

    private static $instance = null;

    private function __construct()
    {
        $this->registerActions();
    }

    public static function boot()
    {
        if (! self::$instance) {
            self::$instance = new AssetDeleted();
        }

        return self::$instance;
    }

    protected function registerActions()
    {
        // Note: Set constants in wp-config
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => AWS_REGION,
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY
            ]
        ]);

        add_action('delete_attachment', [$this, 'attachmentDeleted']);
    }

    public function attachmentDeleted($attachmentId)
    {
        $file = get_post_meta($attachmentId, '_wp_attached_file', true);

        $this->getAllMatching($file);
    }

    protected function getAllMatching(string $file)
    {
        $pathParts = pathinfo($file);

        $objects = $this->s3Client->getIterator('ListObjects', array(
            "Bucket" => AWS_BUCKET,
            "Prefix" => AWS_UPLOADS_PREFIX . $pathParts['dirname'],
        ));

        // Pattern to match myfile.jpg with myfile-100x100.jpg, myfile-1024x768.jpg, etc,
        $pattern = '/'
            . preg_quote(AWS_UPLOADS_PREFIX . $pathParts['dirname'] . '/' . $pathParts['filename'], '/')
            . '(.*).' . $pathParts['extension'] . '/';

        foreach ($objects as $object) {
            $found = $object['Key'];
            // Remove only matching files
            if (preg_match($pattern, $found)) {
                $this->removeFromS3($found);
            }
        }
    }

    protected function removeFromS3(string $file)
    {
        $this->s3Client->deleteObject([
            'Bucket' => AWS_BUCKET,
            'Key'    => $file,
        ]);
    }
}
