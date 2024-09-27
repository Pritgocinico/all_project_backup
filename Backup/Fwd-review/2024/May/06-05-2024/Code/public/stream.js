document.onreadystatechange = function () {
  if (document.readyState === "complete") {
    var url = $('.reviewmgr-embed').data('url');
    jQuery(".reviewmgr-embed").append('<iframe width="100%" height="845" src="'+url+'"></iframe>')
  }
}
