const handleFLDConsentForm = (() => {
  const root = document.querySelector("#fld-cookie-consent-root");
  const hasConsentedCookieName = "fld-cookie-has-consented";
  const hasConsented = checkCookie(hasConsentedCookieName);
  const floatingCookieButton = document.querySelector(
    ".floating-cookie-button"
  );

  floatingCookieButton.addEventListener("click", () => {
    root.classList.add("show");
  });

  if (root && hasConsented) {
    root.classList.remove("show");
  }

  const acceptAllCookies = async (cookie_types, hash) =>
    $.ajax({
      url: ajax_object.ajax_url,
      type: "POST",
      data: {
        action: "accept_all_cookies",
        cookie_types,
        hash,
      },
      success: (response) => response,
      error: (error) => false,
    });

  const setConsentCookies = (hash, response) => {
    setCookie("fldCookieConsentHash", hash, 7);
    setCookie("fldCookieConsentUUID", response.uuid, 7);
    setCookie("fldCookieConsentGiven", response.term_ids.join(","));
    root.classList.remove("show");
  };

  if (root) {
    const acceptAll = root.querySelector("#accept-all");
    const confirmChoices = root.querySelector("#confirm-choices");
    const hash_input = root.querySelector("input[name='hash']");
    const hash = hash_input.value;
    const showMoreButtons = Array.from(root.querySelectorAll(".show-cookies"));

    const hashCookie = getCookieValue("fldCookieConsentHash");

    if (hash !== hashCookie || !hashCookie) {
      root.classList.add("show");
    }

    acceptAll.addEventListener("click", async () => {
      const cookie_type_checkboxes = Array.from(
        root.querySelectorAll("input[type='checkbox']")
      );
      const types = cookie_type_checkboxes.map((c) => c.value).join(",");
      const response = await acceptAllCookies(types, hash);

      setConsentCookies(hash, response);
    });

    confirmChoices.addEventListener("click", async () => {
      const cookie_type_checkboxes = Array.from(
        root.querySelectorAll("input[type='checkbox']:checked")
      );
      const types = cookie_type_checkboxes.map((c) => c.value).join(",");
      const response = await acceptAllCookies(types, hash);

      setConsentCookies(hash, response);
    });

    showMoreButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        button.parentElement.nextElementSibling.classList.toggle("show");
      });
    });
  }
})();
