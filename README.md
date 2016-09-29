# sz-bdt-scrap
A scraper that takes all "imageoftheday"-pictures and downloads them.

to get all images of the current month, just run `run_monthly.php`

also there some things to do with this script - if you want to use it as me for my [MagicMirror](https://magicmirror.builders/)

- [x] script can actually run without exceptions
- [ ] download only todays picture
- [ ] save picture with datetime in filename
- [ ] save imagetext and shortlink in exif field instead into json - so you can read it with [exif-js](https://github.com/exif-js/exif-js)
- [ ] improve the viewer (`index.php`) - the current is crappy - it was just a quick and dirty solution...
- [ ] make an actual working [MagicMirror modules](https://github.com/MichMich/MagicMirror/wiki/MagicMirror%C2%B2-Modules#3rd-party-modules)
