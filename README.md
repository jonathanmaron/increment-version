# Increment Version

When working with a CDN, in which CSS, JS and image files have expire headers a long way into the future, it is very convenient to be able to invalidate a cache programmatically when an existing file is overwritten.

For example, when the `styles.min.css` file is re-built, if something changes in the source SASS file.

One way to invalidate the CDN is to change the URI of the resource, for example, by placing a version number in the URI:

    http://www.example.com/application-x.x.x/css/styles.min.css

where `x.x.x` is the version number.

`increment-version` offers an easy-to-use CLI API to maintain a `.version` file in the the root of your project.

Using the `.version` file, your application can then construct the URI containing the version number.

Please note that `increment-version` supports only numeric semantic version numbers, and not semantic version numbers, containing build and pre-release components.

For example, `increment-version` supports `1.2.3`, but does not support `1.2.3-alpha.1+build.12345.ea4f51`.


## Example Usage

### Initialize Your Project

    increment-version /var/www/www.example.com --init

### Set the `.version` File to 0.0.5

    increment-version /var/www/www.example.com --set 0.0.5

### Increment the Major Number of the `.version` File

    increment-version /var/www/www.example.com --major

### Increment the Minor Number of the `.version` File

    increment-version /var/www/www.example.com --minor

### Increment the Patch Number of the `.version` File

    increment-version /var/www/www.example.com --patch

### Increment the Major, Minor and Patch Numbers of the `.version` File

    increment-version /var/www/www.example.com --major --minor --patch


## How to Use in Your Project

- In the build script that compiles SASS to CSS
- In the build script that compiles CoffeeScript to Javascript
- In the build script that optimizes image files

In each of the above cases, it makes sense to increment the `.version` file to force the web browser to download a new version of the resource that has just been created.


## Installation

Installation is via composer:

    cd ~/install-path

    composer create-project jonathanmaron/increment-version

It is recommended to include `~/bin` in your `PATH` variable:

    PATH=$PATH:~/install-path/increment-version/bin

so that `increment-version` is available to the logged in user globally.


## Aliasing the URI

Ín the root public directory of your web project, you should create a directory in which you will store all the resources with a long expires header.

For example, `application-0.0.0`.

Using Apache's mod_alias, you can then create the following rule:

    AliasMatch "^/application-(\d{1,10}).(\d{1,10}).(\d{1,10})/(.*)$" "/var/www/www.example.com/public/application-0.0.0/$4"

which essentially removes the version number from the URI. For example:

    /application-1.2.3/img/logo.jpg

is mapped to:

    /application-0.0.0/img/logo.jpg

This is the actual storage location of the file on the file system.

Major, minor and patch numbers of up to 10 digits are supported in the above example.


## Adding the Version to the URI

The easiest way to add the version number to the URI of a resources is to write a helper function and wrap all embedded URIs with this function.

The helper function reads the `.version` in the root directory and adds it to the URI.

And alternative method is two write a function which has access to the entire HTML of the page (Zend Framework Finish Events, for example). The function should simply search for `application-0.0.0` replacing it with the version number stored in .version.



And thus the circle is closed.

You simply have to remember to update the .version whenever anything is overwritten and you can then be 100% certain that the web browser will always retrieve the most current version of the resource references in the pages' HTML.
