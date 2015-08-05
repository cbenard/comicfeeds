# Comic Feeds
This application creates feeds from comic feeds that include the images. It does this by crawling the linked pages for images.

Please see my [blog post](http://chrisbenard.net/2013/06/28/how-to-fix-the-dilbert.com-rss-feed/) for more information on background and usage.

## Installing
1. Download and install the [Google App Engine PHP SDK](https://cloud.google.com/appengine/downloads).
2. Download and install the [Google Cloud SDK](https://cloud.google.com/sdk/).
3. Download and install [composer](https://getcomposer.org/doc/00-intro.md) (dependency manager for PHP).
4. Run `composer install` in the application directory to download dependencies into the vendor/ directory.
5. Run `vendor/phpunit/phpunit/phpunit` to execute all tests. 
6. Modify `app.yaml` and replace the `application` and `version` as directed from your Google developer console.

## Contributing
Pull requests are welcome, especially to include more comic providers. Please see the [Dilbert Service](classes/DilbertService.php) (multiple sub-feeds) and [Penny-Arcade Service](classes/PennyArcadeService.php) (single feed) for examples.

## License
This software is licensed under the GPL. See [LICENSE.md](LICENSE.md) for more information.