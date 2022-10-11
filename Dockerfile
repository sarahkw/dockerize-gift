FROM debian:bullseye

# For gift
RUN apt-get -y update && apt-get install -y build-essential git automake libtool libxml-dom-perl libxml-xql-perl libexpat1-dev libxml-writer-perl imagemagick libtext-iconv-perl

# For monosock client
RUN apt-get install -y apache2 libapache2-mod-php php-pear

WORKDIR /usr/share/php
COPY XML_Parser/XML XML

WORKDIR /var/www/html
RUN rm index.html && mkdir collection
COPY PHP_Gift_2004-10-07 .

WORKDIR /

RUN git clone https://git.savannah.gnu.org/git/gift.git && \
    cd gift && \
    git checkout 5cdf6bac7b47747baf0537dccc3a4489d4a3d9cc

WORKDIR /gift

COPY patches-gift patches-gift

RUN patch -p1 < patches-gift/01-tmpnam-deprecated.patch && \
    patch -p1 < patches-gift/02-missing-return-values-undef-behavior.patch && \
    patch -p1 < patches-gift/03-fix-compile-on-modern-compiler.patch && \
    patch -p1 < patches-gift/04-default-config-needs-encoding.patch && \
    patch -p1 < patches-gift/05-undo-patch-causing-segfault.patch

# turn off optimizations, because it is segfaulting otherwise.
RUN ./bootstrap-cvs.sh && ./configure --prefix=/usr CXXFLAGS="-O0" && make -j8 install

CMD gift-add-collection.pl --url-prefix "http://localhost:8080/collection" /var/www/html/collection && \
    service apache2 start && \
    gift

# During development:
#
# docker run --rm -p 8080:80 -v /home/sarah/Work/clipart:/var/www/html/collection -it your_image
