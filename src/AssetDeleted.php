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

        $this->removeFromS3($file);
    }

    protected function removeFromS3(string $file)
    {
        $this->s3Client->deleteObject([
            'Bucket' => 'wordpress-clarku-test',
            'Key'    => 'uploads/' . $file,
        ]);
    }
}
