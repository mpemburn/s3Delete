# s3Delete

This **WordPress** plugin is designed to remove assets from an Amazon S3 bucket when the source file has been removed from WordPress.  It requires an S3 bucket that matches the layout of the `wp-contents` directory.  Several constants are referenced in the code, so these must be set in `wp-config.php`.  They are:

* **AWS_REGION** (e.g., 'us-east-1')
* **AWS_ACCESS_KEY_ID** (obtained from the AWS Console at IAM/Users/[your user]/Security Credentials)
* **AWS_SECRET_ACCESS_KEY** (as above)
* **AWS_BUCKET** (your bucket name)
* **AWS_UPLOADS_PREFIX** (the base path in the bucket)

The **s3Delete** plugin uses `composer` to provide both class autoloading and the AWS S3 SDK. These facilities are already part of this repository.  Should it be necessary to update composer dependecies, delete the `vendor` directory and run `composer install`.
