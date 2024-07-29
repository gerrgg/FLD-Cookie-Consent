function checkCookie(cookieName) {
  // Split cookies by semicolon and trim whitespace
  var cookies = document.cookie.split(";").map((cookie) => cookie.trim());

  // Loop through cookies to find the one we're looking for
  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i];
    // Check if this cookie starts with the name we're looking for
    if (cookie.indexOf(cookieName + "=") === 0) {
      return true; // Cookie found
    }
  }
  return false; // Cookie not found
}

function setCookie(name, value, days) {
  let expires = "";
  if (days) {
    let date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookieValue(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
}

const getCookies = async () =>
  $.ajax({
    url: ajax_object.ajax_url,
    type: "POST",
    data: {
      action: "fetch_cookies",
    },
    success: (response) => response,
    error: (error) => false,
  });

const getConsents = async (uuid) =>
  $.ajax({
    url: ajax_object.ajax_url,
    type: "POST",
    data: {
      action: "fetch_consents",
      uuid,
    },
    success: (response) => response,
    error: (error) => false,
  });
