// =========================
// MAIN FUNCTION
// =========================
function renderSmartContent(rawHtml, mountId) {
    const mount = document.getElementById(mountId);
    if (!mount) return;

    if (!rawHtml) {
        mount.innerHTML = "";
        return;
    }

    const isQuillCode = rawHtml.includes("ql-code-block");
    const isHTML = /<\/?[a-z][\s\S]*>/i.test(rawHtml);

    // TEXT THƯỜNG
    if (!isQuillCode && !isHTML) {
        return renderPlainText(rawHtml, mount);
    }

    // QUILL CODE BLOCK
    if (isQuillCode) {
        return renderQuillSafe(rawHtml, mount);
    }

    // HTML THƯỜNG
    return renderSafeHTML(rawHtml, mount);
}

// =========================
// RENDER TEXT
// =========================
function renderPlainText(text, mount) {
    const div = document.createElement("div");
    div.className = "safe-content";
    div.textContent = text; // escape an toàn

    mount.innerHTML = "";
    mount.appendChild(div);
}

// =========================
// RENDER QUILL
// =========================
function renderQuillSafe(rawHtml, mount) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(rawHtml, "text/html");

    let code = "";

    doc.querySelectorAll(".ql-code-block").forEach(line => {
        code += line.innerHTML.replace(/<br\s*\/?>/gi, "") + "\n";
    });

    const textarea = document.createElement("textarea");
    textarea.innerHTML = code;
    const decodedHTML = textarea.value;

    return renderSafeHTML(decodedHTML, mount);
}

// =========================
// RENDER HTML (CORE)
// =========================
function renderSafeHTML(rawHtml, mount) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(rawHtml, "text/html");

    // =========================
    // 1. REMOVE TAG NGUY HIỂM
    // =========================
    const forbiddenTags = ["script", "iframe", "object", "embed", "link", "meta"];
    forbiddenTags.forEach(tag => {
        doc.querySelectorAll(tag).forEach(el => el.remove());
    });

    // =========================
    // 2. CLEAN ATTRIBUTE
    // =========================
    function cleanNode(node) {
        if (node.nodeType !== 1) return;

        [...node.attributes].forEach(attr => {
            const name = attr.name.toLowerCase();
            const value = attr.value;

            if (name.startsWith("on") || name === "style") {
                node.removeAttribute(name);
                return;
            }

            if (value && value.toLowerCase().includes("javascript:")) {
                node.removeAttribute(name);
            }
        });

        [...node.children].forEach(cleanNode);
    }

    cleanNode(doc.body);

    // =========================
    // 3. FILTER CSS
    // =========================
    let safeCSS = "";

    doc.querySelectorAll("style").forEach(style => {
        const css = style.innerHTML;

        const filtered = css.replace(/([^{}]+)\{([^}]*)\}/g, (match, selector, body) => {
            const safeSelectors = selector
                .split(",")
                .map(s => s.trim())
                .filter(s =>
                    (s.startsWith(".") || s.startsWith("#")) &&
                    !s.includes("body") &&
                    !s.includes("html") &&
                    !s.includes("*")
                );

            if (!safeSelectors.length) return "";

            // prefix tránh đè CSS ngoài
            return safeSelectors
                .map(s => `.safe-content ${s}`)
                .join(", ") + " {" + body + "}";
        });

        safeCSS += filtered;
        style.remove();
    });

    // =========================
    // 4. RENDER
    // =========================
    const wrapper = document.createElement("div");
    wrapper.className = "safe-content";

    const styleTag = document.createElement("style");
    styleTag.innerHTML = safeCSS;

    const content = document.createElement("div");
    content.innerHTML = doc.body.innerHTML;

    wrapper.appendChild(styleTag);
    wrapper.appendChild(content);

    mount.innerHTML = "";
    mount.appendChild(wrapper);
}