/* HiDPI Gravatar Loader © 2012 by Robert Chapin, license: GPL */
if (window.devicePixelRatio > 1.5) {
 avatars = document.getElementsByClassName('avatar');
 for (var i = 0; i < avatars.length; i++) {
  if (avatars[i].tagName != 'IMG') continue;
  lodpi = avatars[i].src;
  if (lodpi.indexOf('.gravatar.com') < 1) continue;
  temp = lodpi.indexOf('&s=');
  if (temp < 10) temp = lodpi.indexOf('?s=');
  if (temp < 10) continue;
  size = parseInt(lodpi.substr(temp + 3));
  avatars[i].src = lodpi.substr(0, temp + 3) + size * 2 + lodpi.substr(temp + 3 + String(size).length);
 }
}
