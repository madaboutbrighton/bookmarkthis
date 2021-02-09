<p align="center">
  <img src="_images/bookmarkthis.png" alt="Logo" width="180" height="180">

  <h1 align="center">BookmarkThis</h1>

  <p align="center">
    A MODX Revolution extra to display social share and follow buttons
    <br />
    <br />
    <a href="https://madaboutbrighton.net/projects/bookmarkthis">website</a>
    ·
    <a href="https://github.com/madaboutbrighton/bookmarkthis/issues">bugs</a>
    ·
    <a href="https://github.com/madaboutbrighton/bookmarkthis/issues">requests</a>
  </p>
</p>

Contents
  - [About The Project](#about-the-project)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Examples](#examples)
  - [Options](#options)

## About The Project

<img src="_images/screenshot1.png" alt="BookmarkThis Screen Shot" width="838" height="638">

BookmarkThis is a MODX Revolution extra that displays inline social share and follow buttons on your website. The BookmarkThis package is made up of several chunks and snippets, and includes very light weight CSS and JavaScript. It is fully customisable, allowing you to add your own links and icons.

### Built With

- [MODX](https://modx.com/)

## Installation

1. Log-in to your MODX Manager
2. Go to the Insaller
3. Search for BookmarkThis and instal

## Usage

### Share

Use the _BookmarkThis_ snippet to dispaly inline social share buttons. By default, all the buttons from the data chunk are shown, where a `share` property has been set.

<img src="_images/share-rounded.png" alt="Logo" width="469" height="110">

### Follow

Use the _BookmarkThisFollow_ snippet to dispaly inline social follow buttons. By default, all the buttons from the data chunk are shown, where a `follow` property has been set.

<img src="_images/follow-rounded.png" alt="Logo" width="528" height="59">

## Examples

### Including only certain items

The `use` property allows you to specify which buttons to show. Buttons returned will be in the same order that you specify them.

<img src="_images/share-rounded-use.png" alt="Logo" width="241" height="60">

### Changing the size

The `size` property allows you set the size of the buttons. You can currenlty choose from _16_, _36_, _48_ and _64_.

<img src="_images/share-rounded-use-size.png" alt="Logo" width="304" height="82">

### Altering the style

The `type` property allows you set the style of the buttons. You can currently choose from _rounded_, _square_, _custom_ and _circle_.

#### Circle icons

<img src="_images/share-circle.png" alt="Logo" width="442" height="210">

#### Custom icons

<img src="_images/share-custom-size.png" alt="Logo" width="441" height="211">

## Options

### Share

The share options are set on the _BookmarkThis_ snippet.

Property | Description | Default
------ | ------|----------
`use` | Comma separated list of items to be used from the data. If empty, all data items will be used. | &nbsp;
`exclude` | Comma separated list of items to be excluded from the data. | &nbsp;
`data` | Name of chunk containing the JSON data items. | _bookmarkThisData_
`type` | Type of icon to be used. Either - _circle_, _rounded_, _custom_ or _square_ | _rounded_
`size` | Size of icon to be used. Either - _16_, _36_, _48_ or _64_ | _36_
`tpl` | Name of chunk to apply to each item. | _bookmarkThisShare_
`tplWrapper` | Name of chunk to wrap all `tpl` items inside. | _bookmarkThisShareWrapper_
`appendJS` | Whether to append the JavaScript to the end of the resource output. | _1_
`appendCSS` | Whether to append the CSS to the end of the resource head. | _1_
`customFields` | JSON data specifying the queries and attributes from which to obtain a bookmarklets _title_, _description_ and _tags_. | &nbsp;

### Follow

The follow options are set on the _BookmarkThisFollow_ snippet.

Property | Description | Default
------ | ------|----------
`use` | Comma separated list of items to be used from the data. If empty, all data items will be used. | &nbsp;
`exclude` | Comma separated list of items to be excluded from the data. | &nbsp;
`data` | Name of chunk containing the JSON data items. | _bookmarkThisData_
`type` | Type of icon to be used. Either - _circle_, _rounded_, _custom_ or _square_ | _rounded_
`size` | Size of icon to be used. Either - _16_, _36_, _48_ or _64_ | _36_
`tpl` | Name of chunk to apply to each item. | _bookmarkThisFollow_
`tplWrapper` | Name of chunk to wrap all `tpl` items inside. | _bookmarkThisFollowWrapper_
`appendCSS` | Whether to append the CSS to the end of the resource head. | _1_

## The data chunk

BookmarkThis can be customised by creating your own data chunk. Doing this means you can add your own social buttons, or edit the existing buttons with your own links and icons.

!!! Simply create a new chunk called _bookmarkThisData_ and fill it with your own data.

### Default data

```json
{
  "blogger":{
      "name" : "Blogger",
      "share" : "https://www.blogger.com/blog-this.g?u={link}&n={title}",
      "follow" : "https://www.blogger.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/blogger-{type}.png"
    },
  "digg":{
      "name" : "Digg",
      "share" : "https://digg.com/submit?url={link}&title={title}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/digg-{type}.png"
    },
  "evernote":{
      "name" : "Evernote",
      "share" : "https://www.evernote.com/clip.action?url={link}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/evernote-{type}.png"
    },
  "facebook":{
      "name" : "Facebook",
      "share" : "https://www.facebook.com/sharer/sharer.php?u={link}&t={title}",
      "follow" : "https://www.facebook.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/facebook-{type}.png"
    },
  "flipboard":{
      "name" : "Flipboard",
      "share" : "https://share.flipboard.com/bookmarklet/popout?v=2&title={title}&url={link}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/flipboard-{type}.png"
    },
  "github":{
      "name" : "Github",
      "follow" : "https://github.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/github-{type}.png"
    }, 
  "google":{
      "name" : "Google",
      "share" : "https://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&labels={tags}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/google-{type}.png"
    },
  "instagram":{
      "name" : "Instagram",
      "follow" : "https://www.instagram.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/instagram-{type}.png"
    }, 
  "linkedin":{
      "name" : "LinkedIn",
      "share" : "https://www.linkedin.com/shareArticle?mini=true&ro=false&trk=bookmarklet&title={title}&url={link}",
      "follow" : "https://www.linkedin.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/linkedin-{type}.png"
    }, 
  "pinterest":{
      "name" : "Pinterest",
      "share" : "https://www.pinterest.com/pin/create/button?url={link}&media=&description={title}",
      "follow" : "https://www.pinterest.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/pinterest-{type}.png"
    },
  "pocket":{
      "name" : "Pocket",
      "share" : "https://getpocket.com/save?url={link}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/pocket-{type}.png"
    },
  "reddit":{
      "name" : "Reddit",
      "share" : "https://reddit.com/submit?url={link}&title={title}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/reddit-{type}.png"
    }, 
  "telegram":{
      "name" : "Telegram",
      "share" : "https://t.me/share/url?url={link}&text={title}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/telegram-{type}.png"
    },
  "tumblr":{
      "name" : "Tumblr",
      "share" : "https://www.tumblr.com/widgets/share/tool?canonicalUrl={link}&title={title}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/tumblr-{type}.png"
    },
  "twitter":{
      "name" : "Twitter",
      "share" : "https://twitter.com/intent/tweet?text={title}&url={link}",
      "follow" : "https://twitter.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/twitter-{type}.png"
    },
  "vk":{
      "name" : "VK",
      "share" : "https://vk.com/share.php?url={link}",
      "follow" : "https://vk.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/vk-{type}.png"
    },
  "youtube":{
      "name" : "Youtube",
      "follow" : "https://www.youtube.com/",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/youtube-{type}.png"
    }, 
  "email":{
      "name" : "E-mail",
      "share" : "mailto:?subject={title}&body={link}",
      "image" : "{bookmarkthis_assets_url}images/v1.8/{size}/email-{type}.png"
    }
}
```
