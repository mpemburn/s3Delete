<?php
/**
 * @package S3Delete
 * @version 1.0.0
 */
/*
Plugin Name: S3Delete
Plugin URI:
Description: Automatically delete assets from S3 bucket on deletion from WordPress.
Author: Mark Pemburn
Version: 1.0.0
Author URI:
*/
namespace S3Delete;

require_once __DIR__ . '/vendor/autoload.php';

use S3Delete\AssetDeleted;

AssetDeleted::boot();


