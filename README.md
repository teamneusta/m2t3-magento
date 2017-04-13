## WORK IN PROGRESS - Neusta M2T3

Configuration of elasticsearch host will be placed in env.php.
Default value will be
Host: elasticsearch
Port: 9200

elasticsearch config block in env.php :
```
array(
    elastic => array(
        default => array(
            host => 'hostname',
            port => port
        )
    )
)
```

(e.g. \TeamNeustaGmbh\M2T3\Model\Elasticsearch\Resource) NOT IMPLEMENTED YET

Parameters:
#### http://base-url.tld?magentypo=head
Returns only content from within `<head>`-Tag.

For example:

```
<script>
    var require = {
        "baseUrl": "http://192.168.0.200/pub/static/frontend/Magento/luma/en_US"
    };
</script>
<link  rel="stylesheet" type="text/css"  media="all" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/mage/calendar.css" />
<link  rel="stylesheet" type="text/css"  media="all" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/css/styles-m.css" />
<link  rel="stylesheet" type="text/css"  media="screen and (min-width: 768px)" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/css/styles-l.css" />
<link  rel="stylesheet" type="text/css"  media="print" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/css/print.css" />
<link  rel="icon" type="image/x-icon" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/Magento_Theme/favicon.ico" />
<link  rel="shortcut icon" type="image/x-icon" href="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/Magento_Theme/favicon.ico" />
<script  type="text/javascript"  src="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/requirejs/require.js"></script>
<script  type="text/javascript"  src="http://192.168.0.200/pub/static/frontend/Magento/luma/en_US/mage/requirejs/mixins.js"></script>
<script  type="text/javascript"  src="http://192.168.0.200/pub/static/_requirejs/frontend/Magento/luma/en_US/requirejs-config.js"></script>
```
#### http://base-url.tld?magentypo=body-class
Returns inly the Magento part of body-Tag content:

```
data-container="body" data-mage-init='{"loaderAjax": {}, "loader": { "icon": "http://localhost/shop/pub/static/frontend/Magento/luma/en_US/images/loader-2.gif"}}' class="cms-home cms-index-index page-layout-1column"
```

#### Routes and Params
All Availiable Block are Rendered inside this route: 
`_fragment/items/all`

with specific Parameters each block is availiable:

##### minicart
```?magentypo=minicart```

