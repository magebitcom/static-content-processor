# Static Content Processor for VSBridnge
Automatically converts Magento urls to VSF urls during indexation

### Installation

* Via composer
    Run `composer require magebit/vsbridge-static-content-procesor` in your root magento directory

* Git clone
    * Create `Magebit` directory in `app/code`
        * `cd app/code`
        * `mkdir Magebit`
    * Clone this repo inside `Magebit` directory
        * `git clone git@github.com:magebitcom/static-content-processor.git`


### Usage
#### Standalone
This module requires you to configure VSF and VSF media urls.   
You can find these configuration fields in:
`Store - Configuration - VueStorefront - Indexer - Static Content Processor`

#### As a dependency

You can also use this module as a dependency for your own  module:

```php

use Magebit\StaticContentProcessor\Helper\Resolver;

class MyModule {
    protected $resolver;

    public function __construct (Resolver $resolver) {
        $this->resolver = $resolver;
    }

    public function example () {
        // This will convert urls and media urls to vsf urls according to the configuration
        return $resolver->resolve($this->getSomeStaticContent());
    }
}


```

---

![Magebit](https://magebit.com/img/magebit-logo-2x.png)

## Authors

* **Kristofers Ozoliņš** (kristofers.ozolins@magebit.com)


