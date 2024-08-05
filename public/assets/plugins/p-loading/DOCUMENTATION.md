## Documentation

### Quick start

We provide compiled CSS and JS (p-loading.*), as well as compiled and minified CSS and JS (p-loading.min.*).

Start including the Javascript at the bottom of the body tag:

```
 <!-- jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>

<!-- P-Loading JS -->
<script type="text/javascript" src="js/p-loading.min.js"></script>
```

Then include the CSS file in the head tag:

```
<link rel="stylesheet" type="text/css" href="css/p-loading.min.css" />
```



### Actions
The property "action" of the settings object that is passed as parameter to the ploading plugin, allowing us to 
##### Show spinner
Use this for show the loading mask in the selected jQuery element.
This will create all the markup of the mask. 
```
$('#test').ploading({action: 'show'});
```
##### Hide spinner
Use this for hide the loading mask in the selected jQuery element.
The markup of the mask will be only hidden. 
```
$('#test').ploading({action: 'hide'});
```

##### Destroy spinner
Use this for destroy the markup of the loading mask. As consecuence the loading mask will disapear.
```
$('#test').ploading({action: 'destroy'});
```

### Add-ons installation

One of our biggest features is the add-on installation. Add custom functions and different spinners to your project with only a few lines of configuration.

##### Download and include the add-on

Select the add-on of your choice, download it manually or with npm. Then, load its Javascript file after the P-Loading Javascript file. 

```
 <!-- jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>

<!-- P-Loading JS -->
<script type="text/javascript" src="dist/js/p-loading.min.js"></script>

<!-- Pl-progress JS -->
<script type="text/javascript" src="dist/js/add-on.min.js"></script>
```

Then, include the CSS file after the P-loading CSS file:

```
<link rel="stylesheet" type="text/css" href="dist/css/p-loading.min.css" />
<link rel="stylesheet" type="text/css" href="dist/css/add-on.min.css" />
```

##### Setting the add-on with P-Loading
One time that the add-on is loaded successfully, then, you should make the P-Loading know that you want to use an add-on.

For do it, just send the useAddOn property with an array containing the names of the add-ons you want to use, inside the ploading function.

```
$('#test').ploading({action: 'hide', useAddOns: ['addOnName']});
```
If you don't want to constantly define  the add-ons inside the useAddOns property, you can set it inside the public settings of p-loading. `$.fn.ploading.defaults`.
```
$.fn.ploading.defaults = {
    //ploading static settings
    useAddOns: ['addOnName']
}
```

Just take in mind that those settings will be executed automatically each time that you'll use the ploading function.
### pluginSettings
Property              | Description       | Default value
-------------         | -------------     | -------------
action                | Executes a function of the pluginPublicAction object | show
maskColor             | Color code to be used as the background of the loading mask | "rgba(0,0,0,0.6)"
containerHTML         | HTML of the container | "<div/>"
containerAttrs        | Container Attributes and custom attributes (class,id,for,etc) | { }
containerClass        | CSS classes of the Container | "p-loading-container"
spinnerHTML           | HTML of the spinner | "<div/>"
spinnerAttrs          | Spinner Attributes and custom attributes (class,id,for,etc) | { }
spinnerClass          | CSS classes of the spinner | "p-loading-spinner piano-spinner"
onShowContainer       | A function to execute when the container get displayed  | undefined
onHideContainer       | A function to execute when the container get hidden  | undefined
onDestroyContainer    | A function to execute when the container is destroyed  | undefined
hideAnimation         | A function that represents the process of how to hide the container | defaultHideAnimation
showAnimation         | A function that represents the process of how to show the container | defaultHideAnimation
destroyAfterHide      | Destoy the container after it gets hidden  | false
idPrefix              | ID prefix of the container  | "loader"
pluginNameSpace       | Namespace of the plugin used in the data attribute of the selected node | "p-loader"
useAddOns             | Contains an array of strings represeting the add-ons to use | [ ]


If you don't want to define a property each time you use the ploading, you can also set the plugin settings into the defaults variable. Just be sure that the ploading JS file is loaded before the default variable is used:
```
$.fn.ploading.defaults = {

};
```

#### containerHTML
Receives a string with the desired HTML that will be used as the "loading mask" container. Example:
```
containerHTML: '<div class="my-custom-container"></div>';
```

#### containerAttrs
Receives an object with the desired attributes that will be used in the "loading mask" container. Example:
```
containerAttrs: {
    id: 'myId',
    customAtribute: '12'
};
```
Note: it uses the jQuery .attr() function.

#### containerClass
Receives a string with the desired CSS classes that will have the "loading mask" container. Example:
```
containerClass: 'my-custom-container';
```

#### spinnerHTML
Receives a string with the desired HTML that will be used as the "spinner". Example:
```
spinnerHTML: 'p-loading-spinner piano-loader';
```

#### spinnerAttrs
Receives an object with the desired attributes that will be used in the spinner. Example:
```
spinnerAttrs: {
    id: 'myId',
    customAtribute: '12'
};
```
Note: it uses the jQuery .attr() function.

#### spinnerClass
Receives a string with the desired CSS classes that will have the " loading mask"container. Example:
```
spinnerClass: 'my-custom-spinner fa-spin fa-spinner';
```

#### onShowContainer ($container, $selectedNode) (Deprecated - use the custom event: pl:spinner:show)
Receives a function (callback) that will be execute when the container is displayed. Example:
```
//Params
$container: "jQuery object of loading mask container"
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
onShowContainer: function ($container, $selectedNode) {
    console.log('the loading mask is displayed');
};
```

#### onHideContainer ($container, $selectedNode) (Deprecated - use the custom event: pl:spinner:hide)
Receives a function (callback) that will be execute when the container is hidden. Example:
```
//Params
$container: "jQuery object of loading mask container"
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
onHideContainer: function ($container, $selectedNode) {
    console.log('the loading mask is hidden');
};
```

#### onDestroyContainer ($selectedNode) (Deprecated - use the custom event: pl:spinner:destroy)
Receives a function (callback) that will be execute when the container is destroyed. Example:
```
//Params
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
onDestroyContainer: function ($selectedNode) {
    console.log('the loading mask is hidden');
};
```

#### destroyAfterHide
Receives a string with a boolean value. If the value is true, then, the "loading mask"s HTML will be destroyed everytime it gets hidden. Example:
```
destroyAfterHide: true;
```

#### idPrefix
Receives a string that will be used as the prefix that is added to the random id of the "loading mask" container. Example:
```
idPrefix: 'loader;
//E.g: result: <div id="loader4234234"></div>
```

#### pluginNameSpace
Receives a string that will be used as the namespace that the plugin uses for save data in the selected HTML node (using jQuery .data() ). Example:
```
pluginNameSpace: 'p-loader';
```

#### maskHolder
Receives a boolean value. If the value is true, then, the plugin will add a css class (p-loading-element-mask) to the selected HTML node. Example:
```
maskHolder: true;
```
### Custom Events

#### After spinner is displayed 
Triggered after the container is displayed. Example:
```
//Params
e: event
$container: "jQuery object of loading mask container"
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
$yourElement.on('pl:spinner:show', function ($container, $selectedNode) {
    console.log('the loading mask is displayed');
})
```

#### After the spinner is hidden
Triggered after the container is hidden. Example:
```
//Params
e: event
$container: "jQuery object of loading mask container"
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
$yourElement.on('pl:spinner:hide', function ($container, $selectedNode) {
    console.log('the loading mask is hidden');
})
```

#### After the spinner is destroyed
Triggered after the container is destroyed. Example:
```
//Params
e: event
$selectedNode: "jQuery object of selected HTML element, e.g: $('.this-element').ploading(..."
```
```
$yourElement.on('pl:spinner:destroy', function ($selectedNode) {
    console.log('the loading mask is hidden');
})
```


### Default Spinners
By default, P-loading includes 3 spinners http://projects.lukehaas.me/css-loaders/ that are based on CSS3 animations (We named them). Feel free to add support to others spinners.

We recommend you to define the spinnerClass in the public defaults variable of the P-lodiang plugin.
Example:
```
$.fn.ploading.defaults = {
    spinnerClass: 'your-spinner-class'
};
```


![alt tag](http://s8.postimg.org/7on1qvi05/spinners.png "Spinners")

Note: depending of your project CSS styles, you may modify the CSS of the P-loading plugin.

#### Piano spinner
Inside the plugin settings, define the spinnerClass as piano-spinner. Example
```
spinnerClass: 'piano-spinner'
```

#### Bubbling spinner
, define the spinnerClass as piano-spinner. Example
```
spinnerClass: 'bubbling-spinner'
```

#### Bubble ride spinner
Inside the plugin settings, define the spinnerClass as piano-spinner. Example
```
spinnerClass: 'bubble-ride-spinner'
```
#### Are you using Font Awesome?
Use the Font Awesome icons as a spinner, adding the next settings to your plugin:

```
$.fn.ploading.defaults = {
    spinnerHTML: '<i></i>',
    spinnerClass: 'fa fa-spinner fa-spin p-loading-fontawesome'
};
```
Replace "fa-spinner" for the Font Awesome's animation you want.
Replace fa-sping for the Font Awesome's icon you want.

The default class for handle Font Awesome icons is: "p-loading-fontawesome".
