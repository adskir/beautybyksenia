/* Lead tracking for GTM / GA4.
   Events pushed to dataLayer:
   - whatsapp_click  : any click on a wa.me link (page_path, link_location)
   - instagram_click : click on the Instagram profile link
   - generate_lead   : pushed inline by the form handlers after Formspree returns OK */
(function () {
  window.dataLayer = window.dataLayer || [];
  document.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!a) return;
    var h = a.getAttribute('href') || '';
    var where = a.className || (a.closest('header') ? 'header' : a.closest('footer') ? 'footer' : 'body');
    if (h.indexOf('wa.me/') !== -1 || h.indexOf('api.whatsapp.com') !== -1) {
      window.dataLayer.push({ event: 'whatsapp_click', page_path: location.pathname, link_location: String(where).slice(0, 60) });
    } else if (h.indexOf('instagram.com') !== -1) {
      window.dataLayer.push({ event: 'instagram_click', page_path: location.pathname });
    }
  }, true);
})();
