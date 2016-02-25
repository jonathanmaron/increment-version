
# increment-version.sh

When working with a CDN, in which CSS, JS and image files have expire headers a long way into the future, it is very convenient to be able to invalidate a cache programmatically when an existing file is overwritten.

For example, when the `styles.min.css` file is re-built, if something changes in the source SASS file.

One way to invalidate the CDN is to change the URI of the resource, for example, by placing a version number in the URI:

    http://www.example.com/application-x.x.x/css/styles.min.css

where `x.x.x` is the version number.

`increment-version.sh` offers an easy-to-use CLI API to maintain a `.version` file in the the root of your project.

Using the `.version` file, your application can then construct the URI containing the version number.
 
Please note that `increment-version.sh` supports only numeric semantic version numbers, and not semantic version numbers, containing build and pre-release components.

For example, `increment-version.sh` supports `1.2.5`, but does not support `1.2.3-alpha.1+build.12345.ea4f51`.
 

## Example usage

### Initialize your project

    increment-version.sh /var/www/www.example.com --init

### Set the `.version` file to 0.0.5

    increment-version.sh /var/www/www.example.com --set 0.0.5

### Increment the major number of the `.version` file

    increment-version.sh /var/www/www.example.com --major

### Increment the minor number of the `.version` file

    increment-version.sh /var/www/www.example.com --minor

### Increment the patch number of the `.version` file

    increment-version.sh /var/www/www.example.com --patch
    
### Increment the major, minor and patch numbers of the `.version` file

    increment-version.sh /var/www/www.example.com --major --minor --patch    
    

## How to use in your project

- In the build script that compiles SASS to CSS
- In the build script that compiles CoffeeScript to Javascript 
- In the build script that optimizes image files

In each of the above cases, it makes sense to increment the `.version` file to force the web browser to download a new version of the resource that has just been created. 


## Installation

