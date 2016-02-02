# FB Pinwall

A simple Wordpress plugin for creating a pinwall of the newest images posted on one or more Facebook pages.

Usage:

    [fbpw]
or

    [fbpw fb-pages='pageId' num-posts='numPosts']

## Documentation

The plugin is using Facebooks 2.x API, which requires you to setup an App with a valid key and secret: https://developers.facebook.com/docs/apps/register

A settings page is available for configuring the plugin (Settings -> FB Pinwall)

Available options:
```
Facebook App Key
Facebook App Secret
Facebook Page for Feed
Number of Posts from Feed
```

If **fb-pages** or **num-posts** are set in the shortcode, the options will be overwritten.


The first call is creating a list of thumbnails that are stored locally (first call will usually take longer). The images are served from Facebook.

Images get enlarged using Colorbox: http://www.jacklmoore.com/colorbox/
