# Gutenberg Forms send with REST

Creates a [WordPress REST API](https://developer.wordpress.org/rest-api/) (POST) endpoint which creates a
[Gutenberg Forms](https://wordpress.org/plugins/forms-gutenberg/) entry and sends an email.

This plugin makes it possible to create a Gutenberg Forms entry using the WordPress API. This allows us to create
form entries programmatically. Some possible use-cases for this are:

- Using WordPress as a headless CMS (ie. using a JavaScript framework such as React, Vue, Svelte)
- Integrating an existing WordPress backend to another app or service

## Installation

Note that this plugin naturally has a dependency of
[Gutenberg Forms](https://wordpress.org/plugins/forms-gutenberg/).

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.

## Using the endpoint

The POST endpoint that is created is `/wp/v2/cwp_gf_send_mail`. Naturally, you will to be authenticated in order to
make a POST request. With the body of the request you will need to provide the following data:

| Key       | Type   | Description                                                                                                      |
| --------- | ------ | ---------------------------------------------------------------------------------------------------------------- |
| `attrs`   | Object | The `attrs` object which comes from the WordPress API for the Gutenberg Forms block                              |
| `fields`  | Array  | The fields held in an array of objects. Each field needs to contain a `field_id` and `field_value` respectively. |
| `post_id` | Number | The Post ID that the form originated from.                                                                       |

Below is a breakdown of each property that should be sent in the POST request:

```javascript
attrs: {
  buttonSetting: { disable: false },
  email: "email@example.com",
  formLabel: "Gutenberg Form",
  formType: "standard",
  fromEmail: "{{name-079d49}}, {{email-0ef4dc}}",
  id: "submit-0ad987d5-8bf6-4e5e-8246-9a37b4ad8b9b",
  template: '{"subject":"Email subject","body":"{{all_data}}"}',
};
```

```javascript
fields: [
  {
    field_value: 'Joe Bloggs',
    field_id: '079d49__LS1uYW1lLTA3OWQ0OS1mYWxzZS1uYW1lLW5hbWVfMDc5ZDQ5',
  },
  {
    field_value: 'joebloggs@yahoo.com',
    field_id: '0ef4dc__LS1lbWFpbC0wZWY0ZGMtZmFsc2UtZW1haWwtZW1haWxfMGVmNGRj',
  },
  {
    field_value: "I'm a message",
    field_id:
      'adf2ea__LS1tZXNzYWdlLWFkZjJlYS1mYWxzZS1tZXNzYWdlLW1lc3NhZ2VfYWRmMmVh',
  },
  // …etc.
]
```

```javascript
post_id: 1337
```

## Examples

TODO: Example usage with React, Svelte and Vue.
