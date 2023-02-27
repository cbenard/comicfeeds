# Comic Feeds
This application creates feeds from comic feeds that include the images. It does this by crawling the linked pages for images.

Please see my [blog post](http://chrisbenard.net/2013/06/28/how-to-fix-the-dilbert.com-rss-feed/) for more information on background and usage.

## Dilbert
Dilbert has been removed due to [current events](https://www.cnn.com/2023/02/27/media/dilbert-distributor-scott-adams/index.html) relating to its author.

## Example feeds
* [Penny-Arcade](http://comicfeeds.chrisbenard.net/view/pennyarcade/default)

## Easily Test with Docker
1. [Install Docker](https://docs.docker.com/engine/install/ubuntu/).
2. Run `docker run -e SELF_SERVE=1 --rm -it -p 127.0.0.1:8080:8080 --name comicfeeds clbenard/comicfeeds:latest`
3. Open your web browser to `http://127.0.0.1:8080/view/dilbert/default` to test.
4. Press Ctrl+C to exit.
5. To upgrade to the newest version:
   1. Press Ctrl+C to exit.
   2. Run `docker pull clbenard/comicfeeds:latest`
   3. Run the commands in this section again to restart the application.

## Permanent Installation with Docker
1. [Install Docker](https://docs.docker.com/engine/install/ubuntu/).
2. To control the start/stop with `systemd`, follow [this article](https://blog.container-solutions.com/running-docker-containers-with-systemd) on how to create a systemd unit to start/manage your container.
3. Replace instances of `redis` with `comicfeeds` in the first example.
4. The last `ExecStart=` line should be:

    ```ExecStart=/usr/bin/docker run --rm -it -p 127.0.0.1:9001:9000 --name %n clbenard/comicfeeds:latest```
5. After the `Restart=always` line, add the following line:

    ```RestartSec=5```

    This is necessary because if you have a fast failure, `systemd` will stop trying to restart your service.
6. Configure your nginx or other web browser to `proxy_pass` all requests to the FPM running on your localhost's port `9001` you configured in step 4 (change this port if necessary).
7. Open your web browser to your configured HTTPD's host/path with `/view/dilbert/default` to test.
8. To upgrade to the newest version:
   1. Run `systemctl restart [your systemd unit name here]`

## Custom Install Without Docker
1. Download and install [composer](https://getcomposer.org/doc/00-intro.md) (dependency manager for PHP).
2. Run `composer install` in the application directory to download dependencies into the vendor/ directory.
3. Run `composer dumpautoload -o` in the application directory to generate autoload code for application classes.
4. Run `vendor/phpunit/phpunit/phpunit` to execute all tests.
5. Set up your PHP-FPM, Apache, etc to use the application's web directory as the root with `index.php` as the directory index.
6. Create a cron entry to run `scripts/fetch.php` every 2 hours (or your desired schedule):
    
    ```* */2 * * * /path/to/app/scripts/fetch.php 2>&1```

## Contributing
Pull requests are welcome, especially to include more comic providers. Please see the [Penny-Arcade Service](classes/PennyArcadeService.php) (single feed) for an example.

## License
This software is licensed under the GPL. See [LICENSE.md](LICENSE.md) for more information.