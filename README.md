<p align="left">
    <a href="https://github.com/magebitcom/static-content-processor"><img src="https://img.shields.io/github/v/tag/magebitcom/static-content-processor" /></a>
    <a href="https://packagist.org/packages/magebit/vsbridge-static-content-procesor"><img src="https://img.shields.io/packagist/v/magebit/vsbridge-static-content-procesor" /></a>
</p>

# Static Content Processor for VSBridge
Automatically converts Magento urls to VSF urls during indexation

## Installation

* Via composer
    Run `composer require magebit/vsbridge-static-content-procesor` in your root magento directory

* Git clone
    * Create `Magebit` directory in `app/code`
        * `cd app/code`
        * `mkdir Magebit`
    * Clone this repo inside `Magebit` directory
        * `git clone git@github.com:magebitcom/static-content-processor.git`


## Usage
#### Standalone
This module requires you to configure VSF and VSF media urls.   
You can find these configuration fields in:
`Store - Configuration - VueStorefront - Indexer - Static Content Processor`

You can also specify which category and product attributes to run through the processor. As an example, you
could select product `description` attribute and all the links and images will be converted with VSF urls.

You can also enable Category Image attribute url processor. It will convert all category attributes as image to VSF urls.

#### As a dependency

You can also use this module as a dependency for your own  module:

```php

use Magebit\StaticContentProcessor\Helper\Resolver;

/**
 * @package MyPackage\MyModule
 */
class MyModule
{
    /**
     * @var \Magebit\StaticContentProcessor\Helper\Resolver
     */
    protected $resolver;

    /**
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Does some content processing
     * @return string
     */
    public function example()
    {
        // This will convert urls and media urls to vsf urls according to the configuration
        return $this->resolver->resolve($this->getSomeStaticContent());
    }
}


```

## Contributing
Found a bug, have a feature suggestion or just want to help in general?
Contributions are very welcome! Check out the [list of active issues](https://github.com/magebitcom/static-content-processor/issues) or submit one yourself.

If you're making a bug report, please include as much details as you can and preferably steps to repreduce the issue.
When creating Pull Requests, don't for get to list your changes in the [CHANGELOG](/CHANGELOG.md) and [README](/README.md) files.

---

![Magebit](https://magebit.com/img/magebit-logo-2x.png)

*Have questions or need help? Contact us at info@magebit.com*

