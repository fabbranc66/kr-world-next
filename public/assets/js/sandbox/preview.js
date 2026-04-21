document.querySelectorAll("[data-sandbox-preview]").forEach((node) => {
  node.dataset.preview = "active";
});

document.querySelectorAll(".sandbox-placeholder").forEach((node) => {
  node.addEventListener("mouseenter", () => {
    node.dataset.hover = "1";
  });

  node.addEventListener("mouseleave", () => {
    delete node.dataset.hover;
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
