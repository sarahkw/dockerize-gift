# dockerize GNU gift

## Directory tree

- PHP_Gift_2004-10-07 is the 'monosock' MRML client found on archive.org, with changes to make it run on modern PHP
- XML_Parser is a version of [XML Parser](https://pear.php.net/package/XML_Parser) known to work with the monosock client

## Quick Start

```
$ docker build .
$ docker run --rm -p 8080:80 -v /path/to/your/images:/var/www/html/collection -it your_docker_image_name
```
And then take your browser to http://localhost:8080/.
