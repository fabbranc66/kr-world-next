const appendSandboxScroll = (url) => {
  if (!url) return url;

  const absolute = new URL(url, window.location.href);
  absolute.searchParams.set("scroll_y", String(window.scrollY || window.pageYOffset || 0));

  return absolute.toString();
};

const restoreSandboxScroll = () => {
  const params = new URLSearchParams(window.location.search);
  const scrollValue = Number.parseInt(params.get("scroll_y") || "", 10);

  if (Number.isNaN(scrollValue) || scrollValue < 0) return;

  window.requestAnimationFrame(() => {
    window.scrollTo({ top: scrollValue, behavior: "auto" });
  });
};

const focusSandboxSidebar = () => {
  const sidebar = document.querySelector("[data-sandbox-sidebar]");
  const focusTarget = document.querySelector("[data-sandbox-sidebar-focus]");

  if (!(sidebar instanceof HTMLElement) || !(focusTarget instanceof HTMLElement)) return;

  window.requestAnimationFrame(() => {
    const sidebarTop = sidebar.getBoundingClientRect().top;
    const targetTop = focusTarget.getBoundingClientRect().top;
    const nextTop = sidebar.scrollTop + (targetTop - sidebarTop) - 12;

    sidebar.scrollTo({
      top: Math.max(0, nextTop),
      behavior: "auto",
    });
  });
};

const focusSandboxLeftPanel = () => {
  const panel = document.querySelector("[data-sandbox-left-panel]");
  if (!(panel instanceof HTMLElement)) return;

  const focusKey = panel.getAttribute("data-sandbox-left-focus");
  if (!focusKey) return;
  const focusTarget = panel.querySelector(`[data-sandbox-left-focus-target="${focusKey}"]`);
  if (!(focusTarget instanceof HTMLElement)) return;

  window.requestAnimationFrame(() => {
    const panelTop = panel.getBoundingClientRect().top;
    const targetTop = focusTarget.getBoundingClientRect().top;
    const nextTop = panel.scrollTop + (targetTop - panelTop) - 12;

    panel.scrollTo({
      top: Math.max(0, nextTop),
      behavior: "auto",
    });
  });
};

const formatMeasure = (value, unit) => {
  if (value === null || value === undefined || value === "") return null;
  const numeric = Number.parseFloat(String(value));
  if (Number.isNaN(numeric)) return null;
  const normalized = Number.isInteger(numeric) ? String(numeric) : String(numeric).replace(/\.0+$/, "");
  return `${normalized}${unit === "px" ? "px" : "%"}`;
};

const buildBackgroundStyles = (values, prefix) => {
  const mode = values[`${prefix}_mode`] || "none";
  const color = values[`${prefix}_color`] || "";
  const gradientFrom = values[`${prefix}_gradient_from`] || "#161616";
  const gradientTo = values[`${prefix}_gradient_to`] || "#090909";
  const gradientAngle = values[`${prefix}_gradient_angle`] || "135";
  const imageUrl = values[`${prefix}_image_url`] || "";
  const position = values[`${prefix}_position`] || "center center";
  const size = values[`${prefix}_size`] || "cover";
  const repeat = values[`${prefix}_repeat`] || "no-repeat";
  const attachment = values[`${prefix}_attachment`] || "scroll";
  const blendMode = values[`${prefix}_blend_mode`] || "normal";
  const styles = {};
  const layers = [];

  if (color) styles.backgroundColor = color;

  if (mode === "gradient" || mode === "gradient_image") {
    layers.push(`linear-gradient(${gradientAngle}deg, ${gradientFrom}, ${gradientTo})`);
  }

  if ((mode === "image" || mode === "gradient_image") && imageUrl) {
    const safeUrl = String(imageUrl).replace(/"/g, '\\"');
    layers.push(`url("${safeUrl}")`);
  }

  if (mode === "solid" && !color) {
    styles.backgroundColor = "#101010";
  }

  if (layers.length > 0) {
    styles.backgroundImage = layers.join(", ");
    styles.backgroundPosition = position;
    styles.backgroundSize = size;
    styles.backgroundRepeat = repeat;
    styles.backgroundAttachment = attachment;
    styles.backgroundBlendMode = blendMode;
  } else {
    styles.backgroundImage = "";
    styles.backgroundPosition = "";
    styles.backgroundSize = "";
    styles.backgroundRepeat = "";
    styles.backgroundAttachment = "";
    styles.backgroundBlendMode = "";
  }

  return styles;
};

const applyStyleMap = (element, styles) => {
  if (!(element instanceof HTMLElement)) return;
  Object.entries(styles).forEach(([property, value]) => {
    element.style[property] = value || "";
  });
};

const formValues = (form) => Object.fromEntries(new FormData(form).entries());

const updatePagePreview = (form) => {
  const frame = document.querySelector("[data-sandbox-page-frame]");
  if (!(frame instanceof HTMLElement)) return;

  const values = formValues(form);
  const maxWidth = formatMeasure(values.page_max_width_value, values.page_max_width_unit);
  const padding = formatMeasure(values.page_padding_value, values.page_padding_unit);
  const slotGap = formatMeasure(values.page_slot_gap_value, values.page_slot_gap_unit);

  frame.style.maxWidth = maxWidth || "";
  frame.style.setProperty("--sandbox-page-padding", padding || "");
  frame.style.setProperty("--sandbox-page-slot-gap", slotGap || "");
  applyStyleMap(frame, buildBackgroundStyles(values, "page_background"));
};

const updateHeaderPreview = (form) => {
  const header = document.querySelector("[data-sandbox-preview-header]");
  const navigationSlot = document.querySelector(".sandbox-system-slot[data-slot-key='navigation']");
  if (!(header instanceof HTMLElement)) return;

  const values = formValues(form);
  const brand = header.querySelector("[data-sandbox-preview-header-brand]");
  const brandText = header.querySelector("[data-sandbox-preview-header-brand-text]");
  const brandLogo = header.querySelector("[data-sandbox-preview-header-logo]");
  const nav = header.querySelector("[data-sandbox-preview-header-nav]");
  const layout = header.querySelector("[data-sandbox-preview-header-layout]");
  const padding = formatMeasure(values.header_padding_value, values.header_padding_unit);
  const height = formatMeasure(values.header_height_value, values.header_height_unit);
  const gap = formatMeasure(values.header_gap_value, values.header_gap_unit);
  const logoWidth = formatMeasure(values.header_logo_width_value, values.header_logo_width_unit);
  const logoHeight = formatMeasure(values.header_logo_height_value, values.header_logo_height_unit);
  const logoMaxHeight = formatMeasure(values.header_logo_max_height_value, values.header_logo_max_height_unit);
  const logoUrl = values.header_logo_url || "";

  if (brand instanceof HTMLElement) {
    brand.setAttribute("aria-label", values.header_brand_label || brand.getAttribute("aria-label") || "KR World");
  }

  if (brandText instanceof HTMLElement) {
    brandText.textContent = values.header_brand_label || brandText.textContent || "KR World";
  }

  if (brandLogo instanceof HTMLImageElement) {
    brandLogo.src = logoUrl || "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
    brandLogo.alt = values.header_brand_label || brandLogo.alt || "KR World";
    brandLogo.classList.toggle("is-active", Boolean(logoUrl));
    brandLogo.style.width = logoWidth || "";
    brandLogo.style.height = logoHeight || "";
    brandLogo.style.maxHeight = logoMaxHeight || "";
    brandLogo.style.objectFit = values.header_logo_scale_mode || "contain";

    if (logoUrl) {
      const widthInput = form.querySelector("[data-sandbox-proportional-group='header-logo'][data-sandbox-proportional-role='width']");
      const heightInput = form.querySelector("[data-sandbox-proportional-group='header-logo'][data-sandbox-proportional-role='height']");
      const maxHeightInput = form.querySelector("input[name='header_logo_max_height_value']");

      if (widthInput instanceof HTMLInputElement && heightInput instanceof HTMLInputElement) {
        brandLogo.addEventListener("load", () => {
          const naturalWidth = brandLogo.naturalWidth || 0;
          const naturalHeight = brandLogo.naturalHeight || 0;

          if ((!widthInput.value || !heightInput.value) && naturalWidth > 0 && naturalHeight > 0) {
            widthInput.value = String(naturalWidth);
            heightInput.value = String(naturalHeight);
            if (maxHeightInput instanceof HTMLInputElement && !maxHeightInput.value) {
              maxHeightInput.value = String(naturalHeight);
            }
          }
        }, { once: true });
      }
    }
  }

  if (nav instanceof HTMLElement) {
    nav.classList.toggle("is-hidden", values.header_navigation_mode === "hide");
  }

  if (navigationSlot instanceof HTMLElement) {
    navigationSlot.classList.toggle("is-hidden", values.header_navigation_mode === "hide");
  }

  if (layout instanceof HTMLElement) {
    layout.className = `sandbox-preview-shell__header sandbox-preview-shell__header--${values.header_layout_mode || "split"}`;
    layout.setAttribute("data-sandbox-preview-header-layout", values.header_layout_mode || "split");
  }

  header.classList.toggle("is-hidden-preview", values.header_visibility_mode === "hidden");
  header.style.opacity = values.header_visibility_mode === "hidden" ? "0.38" : "";
  header.style.filter = values.header_visibility_mode === "hidden" ? "saturate(0.55)" : "";
  header.style.setProperty("--sandbox-header-padding", padding || "");
  header.style.setProperty("--sandbox-header-height", height || "");
  header.style.setProperty("--sandbox-header-gap", gap || "");
  applyStyleMap(header, buildBackgroundStyles(values, "header_background"));
};

const updateFooterPreview = (form) => {
  const footer = document.querySelector("[data-sandbox-preview-footer]");
  const footerNavigationSlot = document.querySelector(".sandbox-system-slot[data-slot-key='footer_navigation']");
  if (!(footer instanceof HTMLElement)) return;

  const values = formValues(form);
  const title = footer.querySelector("[data-sandbox-preview-footer-title]");
  const nav = footer.querySelector("[data-sandbox-preview-footer-nav]");
  const layout = footer.querySelector("[data-sandbox-preview-footer-layout]");
  const padding = formatMeasure(values.footer_padding_value, values.footer_padding_unit);
  const height = formatMeasure(values.footer_height_value, values.footer_height_unit);
  const gap = formatMeasure(values.footer_gap_value, values.footer_gap_unit);

  if (title instanceof HTMLElement) {
    title.textContent = values.footer_label || "Footer";
  }

  if (nav instanceof HTMLElement) {
    nav.classList.toggle("is-hidden", values.footer_navigation_mode === "hide");
  }

  if (footerNavigationSlot instanceof HTMLElement) {
    footerNavigationSlot.classList.toggle("is-hidden", values.footer_navigation_mode === "hide");
  }

  if (layout instanceof HTMLElement) {
    layout.className = `sandbox-preview-shell__footer sandbox-preview-shell__footer--${values.footer_layout_mode || "split"}`;
    layout.setAttribute("data-sandbox-preview-footer-layout", values.footer_layout_mode || "split");
  }

  footer.classList.toggle("is-hidden-preview", values.footer_visibility_mode === "hidden");
  footer.style.opacity = values.footer_visibility_mode === "hidden" ? "0.38" : "";
  footer.style.filter = values.footer_visibility_mode === "hidden" ? "saturate(0.55)" : "";
  footer.style.setProperty("--sandbox-footer-padding", padding || "");
  footer.style.setProperty("--sandbox-footer-height", height || "");
  footer.style.setProperty("--sandbox-footer-gap", gap || "");
  applyStyleMap(footer, buildBackgroundStyles(values, "footer_background"));
};

const updateSlotPreview = (form) => {
  const values = formValues(form);
  const slotKey = values.selected_slot;
  const slot = slotKey ? document.querySelector(`.sandbox-slot[data-slot-key="${slotKey}"]`) : null;
  if (!(slot instanceof HTMLElement)) return;

  const title = slot.querySelector("[data-sandbox-slot-title]");
  const layoutBadge = slot.querySelector("[data-sandbox-slot-layout]");
  const padding = formatMeasure(values.slot_padding_value, values.slot_padding_unit);
  const gap = formatMeasure(values.slot_gap_value, values.slot_gap_unit);

  if (title instanceof HTMLElement) {
    title.textContent = values.slot_label || slotKey;
  }

  if (layoutBadge instanceof HTMLElement) {
    layoutBadge.textContent = values.slot_layout_mode || "stack";
  }

  slot.style.setProperty("--sandbox-slot-padding", padding || "");
  slot.style.setProperty("--sandbox-slot-gap", gap || "");
  slot.style.opacity = values.slot_visibility_mode === "hidden" ? "0.4" : "";
};

const updateBindingPreview = (form) => {
  const values = formValues(form);
  const bindingId = values.binding_id;
  const card = bindingId ? document.querySelector(`.sandbox-binding-card[data-binding-id="${bindingId}"]`) : null;
  if (!(card instanceof HTMLElement)) return;
  const media = card.querySelector("[data-binding-media]");

  const width = formatMeasure(values.width_value, values.width_unit);
  const height = formatMeasure(values.height_value, values.height_unit);
  const minWidth = formatMeasure(values.min_width_value, values.min_width_unit);
  const maxWidth = formatMeasure(values.max_width_value, values.max_width_unit);
  const padding = formatMeasure(values.padding_value, values.padding_unit);
  const gap = formatMeasure(values.gap_value, values.gap_unit);
  const radius = formatMeasure(values.border_radius_value, values.border_radius_unit);
  const alignments = {
    start: "flex-start",
    center: "center",
    end: "flex-end",
    stretch: "stretch",
  };

  card.style.width = width || "";
  card.style.minHeight = height || "";
  card.style.minWidth = minWidth || "";
  card.style.maxWidth = maxWidth || "";
  card.style.padding = padding || "";
  card.style.setProperty("--sandbox-binding-gap", gap || "");
  card.style.borderRadius = radius || "";
  card.style.alignSelf = alignments[values.alignment] || "flex-start";
  card.style.opacity = values.visibility_mode === "hidden" ? "0.45" : "";
  applyStyleMap(card, buildBackgroundStyles(values, "background"));

  if (media instanceof HTMLElement) {
    const imageUrl = values.preview_media_url || "";
    const fitMode = values.media_fit_mode || "cover";
    const ratio = values.media_ratio || "auto";
    const focusX = Number.parseFloat(values.media_focus_x || "50");
    const focusY = Number.parseFloat(values.media_focus_y || "50");
    const hint = media.querySelector(".sandbox-binding-card__media-hint");
    const hasMedia = Boolean(imageUrl || ratio !== "auto" || card.dataset.mediaCapable === "1");

    media.classList.toggle("is-active", hasMedia);
    media.style.backgroundImage = imageUrl ? `url("${String(imageUrl).replace(/"/g, '\\"')}")` : "";
    media.style.backgroundSize = fitMode === "fill" ? "100% 100%" : fitMode;
    media.style.backgroundPosition = `${Number.isNaN(focusX) ? 50 : Math.max(0, Math.min(100, focusX))}% ${Number.isNaN(focusY) ? 50 : Math.max(0, Math.min(100, focusY))}%`;
    media.style.aspectRatio = ratio !== "auto" ? ratio : "";

    if (hint instanceof HTMLElement) {
      hint.textContent = `${fitMode} / ${ratio}`;
    }
  }
};

const attachLivePreview = () => {
  document.querySelectorAll("[data-sandbox-preview-form]").forEach((form) => {
    const handler = () => {
      const scope = form.getAttribute("data-sandbox-preview-form");
      if (scope === "page") updatePagePreview(form);
      if (scope === "header") updateHeaderPreview(form);
      if (scope === "footer") updateFooterPreview(form);
      if (scope === "slot") updateSlotPreview(form);
      if (scope === "binding") updateBindingPreview(form);
    };

    form.addEventListener("input", handler);
    form.addEventListener("change", handler);
  });
};

restoreSandboxScroll();
focusSandboxSidebar();
focusSandboxLeftPanel();
attachLivePreview();

document.querySelectorAll("[data-sandbox-preview]").forEach((node) => {
  node.dataset.preview = "active";
});

document.querySelectorAll(".sandbox-shell a[href]").forEach((link) => {
  link.addEventListener("click", (event) => {
    if (event.defaultPrevented) return;
    if (event.button !== 0) return;
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    const href = link.getAttribute("href");
    if (!href || href.startsWith("#")) return;

    link.href = appendSandboxScroll(href);
  });
});

document.querySelectorAll(".sandbox-shell form").forEach((form) => {
  form.addEventListener("submit", () => {
    let input = form.querySelector("input[name='scroll_y']");

    if (!(input instanceof HTMLInputElement)) {
      input = document.createElement("input");
      input.type = "hidden";
      input.name = "scroll_y";
      form.appendChild(input);
    }

    input.value = String(window.scrollY || window.pageYOffset || 0);
  });
});

document.querySelectorAll("[data-sandbox-source-select]").forEach((select) => {
  select.addEventListener("change", () => {
    const form = select.closest("form");

    if (!form) return;

    form.requestSubmit();
  });
});

document.querySelectorAll("[data-sandbox-logo-picker]").forEach((select) => {
  select.addEventListener("change", () => {
    const group = select.closest("[data-sandbox-media-source]");
    const urlInput = group?.querySelector("[data-sandbox-logo-url-input]");

    if (!(urlInput instanceof HTMLInputElement)) return;

    urlInput.value = select.value || "";
    urlInput.dispatchEvent(new Event("input", { bubbles: true }));
    urlInput.dispatchEvent(new Event("change", { bubbles: true }));
  });
});

document.querySelectorAll("[data-sandbox-media-source]").forEach((group) => {
  const modeSelect = group.querySelector("[data-sandbox-media-mode]");
  const sections = Array.from(group.querySelectorAll("[data-sandbox-media-section]"));
  const preview = group.querySelector("[data-sandbox-logo-preview]");
  const urlInput = group.querySelector("[data-sandbox-logo-url-input]");

  if (!(modeSelect instanceof HTMLSelectElement) || !(urlInput instanceof HTMLInputElement)) return;

  const updateMode = () => {
    const mode = modeSelect.value || "library";

    sections.forEach((section) => {
      section.classList.toggle("is-active", section.getAttribute("data-sandbox-media-section") === mode);
    });
  };

  const updatePreview = () => {
    if (!(preview instanceof HTMLElement)) return;

    const url = urlInput.value.trim();
    preview.classList.toggle("is-active", Boolean(url));
    preview.innerHTML = url
      ? `<img src="${url.replace(/"/g, '&quot;')}" alt="Logo preview">`
      : "<span>Nessun logo selezionato</span>";
  };

  modeSelect.addEventListener("change", updateMode);
  urlInput.addEventListener("input", updatePreview);
  urlInput.addEventListener("change", updatePreview);

  updateMode();
  updatePreview();
});

document.querySelectorAll("form").forEach((form) => {
  const actionInput = form.querySelector("[data-sandbox-header-action]");
  if (!(actionInput instanceof HTMLInputElement)) return;

  form.querySelectorAll("[data-sandbox-submit-action]").forEach((button) => {
    button.addEventListener("click", () => {
      const nextAction = button.getAttribute("data-sandbox-submit-action") || "update_header_options";
      actionInput.value = nextAction;
    });
  });

  form.querySelectorAll("[data-sandbox-upload-input]").forEach((input) => {
    input.addEventListener("change", () => {
      if (!(input instanceof HTMLInputElement) || !input.files || input.files.length === 0) return;

      actionInput.value = "upload_header_logo";
      form.requestSubmit();
    });
  });
});

const proportionalGroups = new Map();

document.querySelectorAll("[data-sandbox-proportional-group]").forEach((input) => {
  const group = input.getAttribute("data-sandbox-proportional-group") || "";
  const role = input.getAttribute("data-sandbox-proportional-role") || "";

  if (!group || !role) return;

  if (!proportionalGroups.has(group)) {
    proportionalGroups.set(group, {});
  }

  proportionalGroups.get(group)[role] = input;
});

proportionalGroups.forEach((pair) => {
  const widthInput = pair.width;
  const heightInput = pair.height;

  if (!(widthInput instanceof HTMLInputElement) || !(heightInput instanceof HTMLInputElement)) return;

  const widthValue = Number.parseFloat(widthInput.value || "");
  const heightValue = Number.parseFloat(heightInput.value || "");
  const initialRatio = !Number.isNaN(widthValue) && !Number.isNaN(heightValue) && heightValue > 0
    ? widthValue / heightValue
    : null;

  if (!initialRatio || initialRatio <= 0) return;

  widthInput.addEventListener("input", () => {
    const nextWidth = Number.parseFloat(widthInput.value || "");
    if (Number.isNaN(nextWidth) || nextWidth <= 0) return;

    const nextHeight = Math.max(1, Math.round((nextWidth / initialRatio) * 100) / 100);
    heightInput.value = String(nextHeight);
    heightInput.dispatchEvent(new Event("input", { bubbles: true }));
  });

  heightInput.addEventListener("input", () => {
    const nextHeight = Number.parseFloat(heightInput.value || "");
    if (Number.isNaN(nextHeight) || nextHeight <= 0) return;

    const nextWidth = Math.max(1, Math.round((nextHeight * initialRatio) * 100) / 100);
    widthInput.value = String(nextWidth);
    widthInput.dispatchEvent(new Event("input", { bubbles: true }));
  });
});

document.querySelectorAll(".sandbox-placeholder").forEach((node) => {
  node.addEventListener("mouseenter", () => {
    node.dataset.hover = "1";
  });

  node.addEventListener("mouseleave", () => {
    delete node.dataset.hover;
  });
});

document.querySelectorAll(".sandbox-slot[data-select-url]").forEach((slot) => {
  slot.addEventListener("click", (event) => {
    const target = event.target;

    if (!(target instanceof HTMLElement)) return;

    if (target.closest(".sandbox-binding-card, .sandbox-slot__link, .sandbox-inline-link, .sandbox-drop-target")) {
      return;
    }

    const url = slot.dataset.selectUrl;

    if (!url) return;

    window.location.href = appendSandboxScroll(url);
  });
});

document.querySelectorAll(".sandbox-system-slot[data-select-url]").forEach((slot) => {
  slot.addEventListener("click", (event) => {
    const target = event.target;

    if (!(target instanceof HTMLElement)) return;

    if (target.closest(".sandbox-binding-card, .sandbox-slot__link, .sandbox-inline-link, .sandbox-drop-target")) {
      return;
    }

    const url = slot.dataset.selectUrl;

    if (!url) return;

    window.location.href = appendSandboxScroll(url);
  });
});

document.querySelectorAll(".sandbox-preview-shell[data-select-url]").forEach((shell) => {
  shell.addEventListener("click", (event) => {
    const target = event.target;

    if (!(target instanceof HTMLElement)) return;

    if (target.closest(".sandbox-slot__link, .sandbox-inline-link")) {
      return;
    }

    const url = shell.dataset.selectUrl;

    if (!url) return;

    window.location.href = appendSandboxScroll(url);
  });
});

document.querySelectorAll("[data-sandbox-field-select]").forEach((select) => {
  select.addEventListener("change", () => {
    const form = select.closest("form");
    const alias = form?.querySelector("[data-sandbox-alias-input]");
    const option = select.selectedOptions[0];

    if (!alias || !option) return;

    alias.value = option.textContent?.trim() || option.value;
  });
});

document.querySelectorAll(".sandbox-binding-card").forEach((card) => {
  card.addEventListener("mouseenter", () => {
    const slotKey = card.dataset.slotKey;
    const slot = slotKey
      ? document.querySelector(`.sandbox-slot[data-slot-key="${slotKey}"]`)
      : null;

    card.dataset.hover = "1";
    if (slot) slot.dataset.hover = "1";
  });

  card.addEventListener("mouseleave", () => {
    const slotKey = card.dataset.slotKey;
    const slot = slotKey
      ? document.querySelector(`.sandbox-slot[data-slot-key="${slotKey}"]`)
      : null;

    delete card.dataset.hover;
    if (slot) delete slot.dataset.hover;
  });
});

let draggedBindingId = null;

const clearDropStates = () => {
  document.querySelectorAll(".sandbox-binding-card.is-dragging, .sandbox-binding-card.is-drop-target").forEach((node) => {
    node.classList.remove("is-dragging", "is-drop-target");
  });

  document.querySelectorAll(".sandbox-slot__canvas.is-drop-ready").forEach((node) => {
    node.classList.remove("is-drop-ready");
  });
};

document.querySelectorAll(".sandbox-binding-card[draggable='true']").forEach((card) => {
  card.addEventListener("dragstart", (event) => {
    draggedBindingId = card.dataset.bindingId || null;
    card.classList.add("is-dragging");

    if (event.dataTransfer) {
      event.dataTransfer.effectAllowed = "move";
      event.dataTransfer.setData("text/plain", draggedBindingId || "");
    }
  });

  card.addEventListener("dragend", () => {
    draggedBindingId = null;
    clearDropStates();
  });

  card.addEventListener("dragover", (event) => {
    if (!draggedBindingId || draggedBindingId === card.dataset.bindingId) return;

    event.preventDefault();
    card.classList.add("is-drop-target");
  });

  card.addEventListener("dragleave", () => {
    card.classList.remove("is-drop-target");
  });

  card.addEventListener("drop", (event) => {
    const template = card.dataset.dropBeforeUrl;

    if (!draggedBindingId || !template) return;

    event.preventDefault();
    window.location.href = appendSandboxScroll(template.replace("__BINDING_ID__", encodeURIComponent(draggedBindingId)));
  });
});

document.querySelectorAll("[data-drop-slot]").forEach((canvas) => {
  canvas.addEventListener("dragover", (event) => {
    if (!draggedBindingId) return;

    event.preventDefault();
    canvas.classList.add("is-drop-ready");
  });

  canvas.addEventListener("dragleave", (event) => {
    if (event.currentTarget !== event.target && canvas.contains(event.relatedTarget)) return;

    canvas.classList.remove("is-drop-ready");
  });
});

document.querySelectorAll(".sandbox-drop-target").forEach((target) => {
  target.addEventListener("dragover", (event) => {
    if (!draggedBindingId) return;

    event.preventDefault();
    target.closest(".sandbox-slot__canvas")?.classList.add("is-drop-ready");
  });

  target.addEventListener("drop", (event) => {
    const template = target.dataset.dropUrl;

    if (!draggedBindingId || !template) return;

    event.preventDefault();
    window.location.href = appendSandboxScroll(template.replace("__BINDING_ID__", encodeURIComponent(draggedBindingId)));
  });
});
