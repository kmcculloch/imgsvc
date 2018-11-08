# Image Derivative Engine

This is a small proof of concept for an image derivative engine built with the Slim framework.

## Installation

```
composer install
docker build -t imgsvc .
docker run --rm -p 8000:80 imgsvc
```

## Scale Images

Once your container is running you'll find the image scale endpoint at `http://localhost:8000/scale/w/{width}/h/{height}/{image}`.

`width` and `height` must be integers between 1 and 2000.

`image` must match the name of one of the images in the `originals` folder (`chain.jpg`, `city.jpg` or `trail.jpg`).

## Development

To enable real-time code editing and error reporting, run:

```
docker run --rm -e SLIM_ENV=dev -v $(pwd):/var/www/imgsvc -p 8000:80 imgsvc
```

To lint, run:

```
composer run phpcs
```
