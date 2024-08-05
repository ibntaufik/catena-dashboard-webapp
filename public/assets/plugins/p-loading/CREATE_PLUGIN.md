With an add-on you are able to create new functionalities for P-Loading (such as custom spinners, loading bars, and more) that can be installed in a easy way.

## Get started.

### Registering your add-on

First thing you should do is to register your add-on in the public variable $.fn.ploading.addOns

    ( function () {
     $.fn.ploading.addOns.myAddOn = function ( api ) {
    
        return api;
     }; 
    } ) ()

### Add-ons Parameters
Then, when your add-on is executed by P-Loading, it'll receive 1 variable passed as parameter. 

We called it "__api__", and it will contain the following structure:

Property             | Summary                                                                                         | Shared
-------------        | -------------                                                                                   | -------------
pluginPublicAction   | contains all the actions of P-Loading ( show, hide, destroy, etc ).                             | true
pluginSettings       | is a literal object that contains the settings used in P-Loading. (`$.fn.ploading(settings)`)   | true
pluginPrivateAction  | contains helpful tools for "don't repeat yourself"                                              | false
$pluginElement       |   contains a jQuery object of the element that the user selected for use with P-Loading         | true

All the properties that have the value of __shared__ as true ( pluginPublicAction, pluginSettings ) means that if they get modified, those changes will be applied on the same objects that P-Loading has (only by returning the modified api object in yours add-on's code). And here is where you'll be playing. 
    
### Into details

Here is a description about how is composed each of the api objects. 

#### pluginSettings
Property              | Description       | Default value
-------------         | -------------     | -------------
action                | Executes a function of the pluginPublicAction object | show
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

#### pluginPublicAction
Function              | Description       
-------------         | -------------    
show          | Use this for show the loading mask in the selected jQuery element. This will create all the markup of the mask.
hide          | Use this for hide the loading mask in the selected jQuery element. The markup of the mask will be only hidden.
destroy       | Use this for destroy the markup of the loading mask. As consecuence, the loading mask will disapear


#### pluginPrivateAction

For now, pluginPrivateAction only has one object called __utils__ that contains small functions for help you the development process.

For that reason, __we'll describe only__ the properties of the __utils object of__ the __pluginPrivateAction__ object.

Function              | Description       
-------------         | -------------    
getPluginContainerId  | Returns the ID of the container created dynamically by P-Loading that contains the spinner
getPluginContainer    | Returns the jQuery object of the container created dynamically by P-Loading that contains the spinner


- - - -
### Creating my first add-on
For have a better understanding of how the P-Loading's add-ons work, we'll create a small loading mask with a progress bar inside.

For that, __we'll use the following CSS file:__ [Progress bar CSS File](google.com)

Going back to the first thing we explained at the begin, first we need to register our addOn in the plublic variable addOns.

#### Registering your add-on

First thing you should do is __to register your add-on__ in the public variable $.fn.ploading.addOns.
__Let's call our addOn "progressSpinner"__

    ( function () {
     $.fn.ploading.addOns.progressSpinner = function ( api ) {
    
        return api;
     }; 
    } ) ()
    
#### Adding your custom HTML 

Now is time to __add our custom HTML and CSS classes by modifying the the next properties__ of the `pluginSettings` object.

    ( function () {
     $.fn.ploading.addOns.progressSpinner = function ( api ) {
        var setPluginMarkUp = function () {
            api.pluginSettings.containerHTML = '<div>';
            api.pluginSettings.containerClass = 'p-loading-container pl-progress-spinner-container';
            api.pluginSettings.spinnerClass = 'pl-progress-spinner';
            //The spinner will be a progress tag
            api.pluginSettings.spinnerHTML = '<progress max="100" value="0"></progress>';
        };
        
        return api;
     }; 
     
#### Adding your custom actions 

Let's add our __custom action for update and display the progress bar__. For this, we'll add a new function to the api.pluginPublicAction object ( __contains all the public actions__ used in the ploading plugin).

    ( function () {
     $.fn.ploading.addOns.progressSpinner = function ( api ) {
        var setAddOnMarkUp = function () {
            api.pluginSettings.containerHTML = '<div>';
            api.pluginSettings.containerClass = 'p-loading-container pl-progress-spinner-container';
            api.pluginSettings.spinnerClass = 'pl-progress-spinner';
            //The spinner will be a progress tag
            api.pluginSettings.spinnerHTML = '<progress max="100" value="0"></progress>';
        };
        var setAddOnActions = function () {
            api.pluginPublicAction.updateProgress = function() {
                //We'll show the progress bar in case it's hidden, by using the default "show" function
                api.pluginPublicAction.show();
                
                //if the user sent the "progress" value
                if ( api.pluginSettings.progress ) {
                    //We'll update the value of progress bar, by using the "api.pluginSettings.progress" value
                    api.$pluginElement.find('.pl-progress-spinner').prop('value', api.pluginSettings.progress);
                }
            };
        };
        return api;
     }; 
     
    
    } ) ()
    
You may be wondering what is the `api.pluginSettings.progress` variable. It's a new property that we'll expected to receive the pluginSettings object.

#### Adding custom properties to api.pluginSettings.

This is very easy. Knowing that `api.pluginSettings` represents the object we pass here `$.ploading(options)`, where `options` could be `{action: 'show', progress: 100, more: 'more'}`. Then, all that properties added there should be accessable from the  `api.pluginSettings` object.

So, if we need a new property for update the progress value of the "progress bar", then, we only should use the `api.pluginSettings.progress` property. Expecting that the user sent it throught the settings parameters.


#### Initialize and Voilà! 
The __only thing missing__ in our code is to __call the functions that we created__, so, let's create a function called "initialize" that __will apply our changes to the api object__, and finally __return the api object__ to the ploading plugin.


    ( function () {
     $.fn.ploading.addOns.progressSpinner = function ( api ) {
        var setAddOnMarkUp = function () {
            api.pluginSettings.containerHTML = '<div>';
            api.pluginSettings.containerClass = 'p-loading-container pl-progress-spinner-container';
            api.pluginSettings.spinnerClass = 'pl-progress-spinner';
            //The spinner will be a progress tag
            api.pluginSettings.spinnerHTML = '<progress max="100" value="0"></progress>';
        };
        var setAddOnActions = function () {
            api.pluginPublicAction.updateProgress = function() {
                //We'll show the progress bar in case it's hidden, by using the default "show" function
                api.pluginPublicAction.show();
                
                //if the user sent the "progress" value
                if ( api.pluginSettings.progress ) {
                    //We'll update the value of progress bar, by using the "api.pluginSettings.progress" value
                   api.$pluginElement.find('.pl-progress-spinner').prop('value', api.pluginSettings.progress);
                }
            };
        };
        
        var initialize = function () {
            setAddOnMarkUp();
            setAddOnActions();
        };
        
        initialize();
        
        return api;
     }; 
     
    
    } ) ()

So, the __usage of our progress bar addOn__ should be like this:

    $('.default-spinner-example').ploading({
        action: 'updateProgress',
        useAddOns: ['progressSpinner'],
        progress: 90
    })
