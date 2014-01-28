advanced_custom_data
====================

Plugin for custom data field management in Zenphoto

Installation:
Put in zenphoto/plugin folder, activate in backend, plugins tab.

Use admin utility functions (in "overview") to create or delete data fields. 
You can get a specific custom data field with `getAcd(type,name)` in your theme.
Type can be "image" or "album" to get the custom data of the current image or the current album.
Name is your field's name.

This is under development, use at your own risk.
