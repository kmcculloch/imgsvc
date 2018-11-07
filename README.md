# Image Derivative Engine

This is a small proof of concept for an image derivative engine built with the Slim framework.

## Installation

```
composer install
docker build -t imgsvc .
docker run --rm -p 8000:80 imgsvc
```

Navigate to http://localhost:8000/ and confirm that you see "Hello world".

## Scale Images

The image scale endpoint is `/scale/w/{width}/h/{height}/{image}`.
`width` and `height` must be integers between 1 and 2000.
`image` must match the name of one of the images in the `originals` folder (`chain.jpg`, `city.jpg` or `trail.jpg`).
